<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use App\Models\SubscriptionPlan;
use App\Models\Image;
use App\Models\User;
use App\Models\ApiKey;
use App\Services\Service;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ImageController extends Controller
{
    private $api;
    private $user;

    public function __construct()
    {
        $this->api = new LicenseController();
        $this->user = new UserService();
    }

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data = Image::where('user_id', Auth::user()->id)->latest()->limit(18)->get();
        $records = Image::where('user_id', Auth::user()->id)->count();

        if (auth()->user()->plan_id) {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan) {
                $openai_model = ($plan->dalle_image_engine != 'none') ? $plan->dalle_image_engine : 'none';
                $sd_model = ($plan->sd_image_engine != 'none') ? $plan->sd_image_engine : 'none';
                \Log::info($plan);
                \Log::info($openai_model);
            } else {
                $openai_model = 'none';
                $sd_model = 'none';
            }
        } else {
            $openai_model = 'none';
            $sd_model = 'none';
        }       

        return view('user.images.index', compact('data', 'records', 'openai_model', 'sd_model'));
    }


    /**
	*
	* Process Davinci Image
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function process(Request $request) 
    {
        if ($request->ajax()) {

            if (config('settings.personal_openai_api') == 'allow') {
                if (is_null(auth()->user()->personal_openai_key)) {
                    $data['status'] = 'error';
                    $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                    return $data; 
                } else {
                    $open_ai = new OpenAi(auth()->user()->personal_openai_key);
                } 
    
            } elseif (!is_null(auth()->user()->plan_id)) {
                $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                if ($check_api->personal_openai_api) {
                    if (is_null(auth()->user()->personal_openai_key)) {
                        $data['status'] = 'error';
                        $data['message'] = __('You must include your personal Openai API key in your profile settings first');
                        return $data; 
                    } else {
                        $open_ai = new OpenAi(auth()->user()->personal_openai_key);
                    }
                } else {
                    if (config('settings.openai_key_usage') !== 'main') {
                       $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                       array_push($api_keys, config('services.openai.key'));
                       $key = array_rand($api_keys, 1);
                       $open_ai = new OpenAi($api_keys[$key]);
                   } else {
                       $open_ai = new OpenAi(config('services.openai.key'));
                   }
               }
    
            } else {
                if (config('settings.openai_key_usage') !== 'main') {
                    $api_keys = ApiKey::where('engine', 'openai')->where('status', true)->pluck('api_key')->toArray();
                    array_push($api_keys, config('services.openai.key'));
                    $key = array_rand($api_keys, 1);
                    $open_ai = new OpenAi($api_keys[$key]);
                } else {
                    $open_ai = new OpenAi(config('services.openai.key'));
                }
            }

            $verify = $this->user->verify_license();
            if($verify['status']!=true){return false;}

            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            $results = [];

            # Check if user has access to the template
            if (auth()->user()->group == 'user') {
                if (config('settings.image_feature_user') != 'allow') {
                    $data['status'] = 'error';
                    $data['message'] = __('AI Image feature is not available for your account, subscribe to get access');
                    return $data;
                }
            } elseif (!is_null(auth()->user()->group)) {
                if ($plan) {
                    if (!$plan->image_feature) {
                        $data['status'] = 'error';
                        $data['message'] = __('AI Image feature is not available for your subscription plan');
                        return $data;
    
                    }
                }
            }             

            # Verify if user has enough credits
            if (auth()->user()->available_images != -1) {
                if ((auth()->user()->available_images + auth()->user()->available_images_prepaid) < $request->max_results) {
                    if (!is_null(auth()->user()->member_of)) {
                        if (auth()->user()->member_use_credits_image) {
                            $member = User::where('id', auth()->user()->member_of)->first();
                            if (($member->available_images + $member->available_images_prepaid) < $request->max_results) {
                                $data['status'] = 'error';
                                $data['message'] = __('Not enough image balance to proceed, subscribe or top up your image balance and try again');
                                return $data;
                            }
                        } else {
                            $data['status'] = 'error';
                            $data['message'] = __('Not enough image balance to proceed, subscribe or top up your image balance and try again');
                            return $data;
                        }
                        
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = __('Not enough image balance to proceed, subscribe or top up your image balance and try again');
                        return $data;
                    } 
                }
            }


            $max_results = (int)$request->max_results;
            $plan_type = (auth()->user()->plan_id) ? 'paid' : 'free';  

            $prompt = $request->prompt;
            
            if ($request->style != 'none') {
                $prompt .= ', ' . $request->style; 
            } 
            
            if ($request->lightning != 'none') {
                $prompt .= ', ' . $request->lightning; 
            } 
            
            if ($request->artist != 'none') {
                $prompt .= ', ' . $request->artist; 
            }
            
            if ($request->medium != 'none') {
                $prompt .= ', ' . $request->medium; 
            }
            
            if ($request->mood != 'none') {
                $prompt .= ', ' . $request->mood; 
            }


            if ($request->vendor == 'openai') {

                if ($plan) {
                    if (is_null($plan->dalle_image_engine)) {
                        $model = 'dall-e-2';    
                    } elseif ($plan->dalle_image_engine == 'none') {
                        $data['status'] = 'error';
                        $data['message'] = __('Your subscription plan does not cover Dalle models');
                        return $data;
                    } else {
                        $model = $plan->dalle_image_engine;
                    }
                } else {
                    $model = config('settings.image_dalle_engine'); 
                }

                if ($model == 'dall-e-3-hd') {
                    $complete = $open_ai->image([
                        'model' => 'dall-e-3',
                        'prompt' => $prompt,
                        'size' => $request->resolution,
                        'n' => $max_results,
                        "response_format" => "url",
                        'quality' => "hd",
                    ]);
                } else {
                    $complete = $open_ai->image([
                        'model' => $model,
                        'prompt' => $prompt,
                        'size' => $request->resolution,
                        'n' => $max_results,
                        "response_format" => "url",
                        'quality' => "standard",
                    ]);
                }               

                $download = new Service();
                $status = $download->prompt2();
                if($status['status']!=true){return false;}

                $response = json_decode($complete , true);

                if (isset($response['data'])) {
                    if (count($response['data']) > 1) {
                        foreach ($response['data'] as $key => $value) {
                            $url = $value['url'];

                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl, CURLOPT_URL, $url);
                            $contents = curl_exec($curl);
                            curl_close($curl);

                            $name = 'dalle-' . Str::random(10) . '.png';

                            if (config('settings.default_storage') == 'local') {
                                Storage::disk('local')->put('images/' . $name, $contents);
                                $image_url = 'images/' . $name;
                                $storage = 'local';
                            } elseif (config('settings.default_storage') == 'aws') {
                                Storage::disk('s3')->put('images/' . $name, $contents, 'public');
                                $image_url = Storage::disk('s3')->url('images/' . $name);
                                $storage = 'aws';
                            } elseif (config('settings.default_storage') == 'wasabi') {
                                Storage::disk('wasabi')->put('images/' . $name, $contents);
                                $image_url = Storage::disk('wasabi')->url('images/' . $name);
                                $storage = 'wasabi';
                            }

                            if ($plan) {
                                if (is_null($plan->image_storage_days)) {
                                    if (config('settings.default_duration') == 0) {
                                        $expiration = Carbon::now()->addDays(18250);
                                    } else {
                                        $expiration = Carbon::now()->addDays(config('settings.default_duration'));
                                    }                            
                                } else {
                                    if ($plan->image_storage_days == 0) {
                                        $expiration = Carbon::now()->addDays(18250);
                                    } else {
                                        $expiration = Carbon::now()->addDays($plan->image_storage_days);
                                    }
                                }
                            } else {
                                if (config('settings.default_duration') == 0) {
                                    $expiration = Carbon::now()->addDays(18250);
                                } else {
                                    $expiration = Carbon::now()->addDays(config('settings.default_duration'));
                                } 
                            }

                            $content = new Image();
                            $content->user_id = auth()->user()->id;
                            $content->description = $request->prompt;
                            $content->resolution = $request->resolution;
                            $content->image = $image_url;
                            $content->plan_type = $plan_type;
                            $content->storage = $storage;
                            $content->expires_at = $expiration;
                            $content->image_name = 'images/' . $name;
                            $content->vendor = 'dalle';
                            $content->image_style = $request->style;
                            $content->image_lighting = $request->lightning;
                            $content->image_artist = $request->artist;
                            $content->image_mood = $request->mood;
                            $content->image_medium = $request->medium;
                            $content->vendor_engine = $model;
                            $content->save();

                            $image_result = $this->createImageBox($content);
                            array_push($results, $image_result);
                        }
                    } else {
                        $url = $response['data'][0]['url'];

                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_URL, $url);
                        $contents = curl_exec($curl);
                        curl_close($curl);


                        $name = 'dalle-' . Str::random(10) . '.png';

                        if (config('settings.default_storage') == 'local') {
                            Storage::disk('local')->put('images/' . $name, $contents);
                            $image_url = 'images/' . $name;
                            $storage = 'local';
                        } elseif (config('settings.default_storage') == 'aws') {
                            Storage::disk('s3')->put('images/' . $name, $contents, 'public');
                            $image_url = Storage::disk('s3')->url('images/' . $name);
                            $storage = 'aws';
                        } elseif (config('settings.default_storage') == 'wasabi') {
                            Storage::disk('wasabi')->put('images/' . $name, $contents);
                            $image_url = Storage::disk('wasabi')->url('images/' . $name);
                            $storage = 'wasabi';
                        }

                        if ($plan) {
                            if (is_null($plan->image_storage_days)) {
                                if (config('settings.default_duration') == 0) {
                                    $expiration = Carbon::now()->addDays(18250);
                                } else {
                                    $expiration = Carbon::now()->addDays(config('settings.default_duration'));
                                }                            
                            } else {
                                if ($plan->image_storage_days == 0) {
                                    $expiration = Carbon::now()->addDays(18250);
                                } else {
                                    $expiration = Carbon::now()->addDays($plan->image_storage_days);
                                }
                            }
                        } else {
                            if (config('settings.default_duration') == 0) {
                                $expiration = Carbon::now()->addDays(18250);
                            } else {
                                $expiration = Carbon::now()->addDays(config('settings.default_duration'));
                            } 
                        }

                        $content = new Image();
                        $content->user_id = auth()->user()->id;
                        $content->description = $request->prompt;
                        $content->resolution = $request->resolution;
                        $content->image = $image_url;
                        $content->plan_type = $plan_type;
                        $content->storage = $storage;
                        $content->expires_at = $expiration;
                        $content->image_name = 'images/' . $name;
                        $content->vendor = 'dalle';
                        $content->image_style = $request->style;
                        $content->image_lighting = $request->lightning;
                        $content->image_artist = $request->artist;
                        $content->image_mood = $request->mood;
                        $content->image_medium = $request->medium;
                        $content->vendor_engine = $model;
                        $content->save();

                        $image_result = $this->createImageBox($content);
                        array_push($results, $image_result);
                    }
                    
                    # Update credit balance
                    $this->updateBalance($max_results);

                    $data['status'] = 'success';
                    $data['images'] = $results;
                    $data['old'] = auth()->user()->available_images + auth()->user()->available_images_prepaid;
                    $data['current'] = auth()->user()->available_images + auth()->user()->available_images_prepaid - $max_results;
                    return $data; 

                } else {

                    $message = $response['error']['message'];

                    $data['status'] = 'error';
                    $data['message'] = $message;
                    return $data;
                }

            } elseif ($request->vendor == 'stable_diffusion') {

                if ($plan) {
                    if (is_null($plan->sd_image_engine)) {
                        $sd_model = config('settings.image_stable_diffusion_engine');   
                    } elseif ($plan->sd_image_engine == 'none') {
                        $data['status'] = 'error';
                        $data['message'] = __('Your subscription plan does not cover Stable Diffusion models');
                        return $data;
                    } else {
                        $sd_model = $plan->sd_image_engine;
                    }
                } else {
                    $sd_model = config('settings.image_stable_diffusion_engine');
                }

                $url = 'https://api.stability.ai/v1/generation/' . $sd_model. '/text-to-image';

                if (config('settings.personal_sd_api') == 'allow') {
                    if (is_null(auth()->user()->personal_sd_key)) {
                        $data['status'] = 'error';
                        $data['message'] = __('You must include your personal Stable Diffusion API key in your profile settings first');
                        return $data; 
                    } else {
                        $stable_diffusion = auth()->user()->personal_sd_key;
                    } 
        
                } elseif (!is_null(auth()->user()->plan_id)) {
                    $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                    if ($check_api->personal_sd_api) {
                        if (is_null(auth()->user()->personal_sd_key)) {
                            $data['status'] = 'error';
                            $data['message'] = __('You must include your personal Stable Diffusion API key in your profile settings first');
                            return $data; 
                        } else {
                            $stable_diffusion = auth()->user()->personal_sd_key;
                        }
                    } else {
                        if (config('settings.sd_key_usage') == 'main') {
                            $stable_diffusion = config('services.stable_diffusion.key');
                        } else {
                            $api_keys = ApiKey::where('engine', 'stable_diffusion')->where('status', true)->pluck('api_key')->toArray();
                            array_push($api_keys, config('services.stable_diffusion.key'));
                            $key = array_rand($api_keys, 1);
                            $stable_diffusion = $api_keys[$key];
                        }
                    }
        
                } else {
                    if (config('settings.sd_key_usage') == 'main') {
                        $stable_diffusion = config('services.stable_diffusion.key');
                    } else {
                        $api_keys = ApiKey::where('engine', 'stable_diffusion')->where('status', true)->pluck('api_key')->toArray();
                        array_push($api_keys, config('services.stable_diffusion.key'));
                        $key = array_rand($api_keys, 1);
                        $stable_diffusion = $api_keys[$key];
                    }
                }

                $headers = [
                            'Authorization:' . $stable_diffusion, 
                            'Content-Type: application/json',
                        ];
                   
                $resolutions = explode('x', $request->resolution_sd);
                $width = $resolutions[0];
                $height = $resolutions[1];
                $data['text_prompts'][0]['text'] = $prompt;
                $data['text_prompts'][0]['weight'] = 1;
                if (request('enable-negative-prompt') == 'on') {
                    if (!is_null($request->negative_prompt)) {
                        $data['text_prompts'][1]['text'] = $request->negative_prompt;
                        $data['text_prompts'][1]['weight'] = -1;
                    }                    
                }
                $data['clip_guidance_preset'] = $request->preset;
                $data['height'] = (int)$height; 
                $data['width'] = (int)$width; 
                $data['steps'] = (int)$request->steps; 
                $data['cfg_scale'] = (int)$request->cfg_scale; 
                if ($request->diffusion_samples != 'none') {
                    $data['sampler'] = $request->diffusion_samples;
                }
                $data['samples'] = $max_results;
                if ($request->style != 'none') {
                    $data['style_preset'] = $request->style;
                }

                $upload = new Service();
                $status = $upload->prompt2();
                if($status['status']!=true){return false;}

                $postdata = json_encode($data);

                $ch = curl_init($url); 
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                curl_close($ch);

                $response = json_decode($result , true);
       
                if (isset($response['artifacts'])) {
                    foreach ($response['artifacts'] as $key => $value) {

                        $image = base64_decode($value['base64']);

                        $name = 'sd-' . Str::random(10) . '.png';

                        if (config('settings.default_storage') == 'local') {
                            Storage::disk('local')->put('images/' . $name, $image);
                            $image_url = 'images/' . $name;
                            $storage = 'local';
                        } elseif (config('settings.default_storage') == 'aws') {
                            Storage::disk('s3')->put('images/' . $name, $image, 'public');
                            $image_url = Storage::disk('s3')->url('images/' . $name);
                            $storage = 'aws';
                        } elseif (config('settings.default_storage') == 'wasabi') {
                            Storage::disk('wasabi')->put('images/' . $name, $image);
                            $image_url = Storage::disk('wasabi')->url('images/' . $name);
                            $storage = 'wasabi';
                        }

                        if ($plan) {
                            if (is_null($plan->image_storage_days)) {
                                if (config('settings.default_duration') == 0) {
                                    $expiration = Carbon::now()->addDays(18250);
                                } else {
                                    $expiration = Carbon::now()->addDays(config('settings.default_duration'));
                                }                            
                            } else {
                                if ($plan->image_storage_days == 0) {
                                    $expiration = Carbon::now()->addDays(18250);
                                } else {
                                    $expiration = Carbon::now()->addDays($plan->image_storage_days);
                                }
                            }
                        } else {
                            if (config('settings.default_duration') == 0) {
                                $expiration = Carbon::now()->addDays(18250);
                            } else {
                                $expiration = Carbon::now()->addDays(config('settings.default_duration'));
                            } 
                        }

                        $content = new Image();
                        $content->user_id = auth()->user()->id;
                        $content->description = $request->prompt;
                        $content->resolution = $request->resolution_sd;
                        $content->image = $image_url;
                        $content->plan_type = $plan_type;
                        $content->storage = $storage;
                        $content->expires_at = $expiration;
                        $content->image_name = 'images/' . $name;
                        $content->vendor = 'sd';
                        $content->image_style = $request->style;
                        $content->image_lighting = $request->lightning;
                        $content->image_artist = $request->artist;
                        $content->image_mood = $request->mood;
                        $content->image_medium = $request->medium;
                        $content->negative_prompt = $request->negative_prompt;
                        $content->sd_clip_guidance = $request->preset;
                        $content->sd_prompt_strength = $request->cfg_scale;
                        $content->sd_diffusion_samples = $request->diffusion_samples;
                        $content->sd_steps = $request->steps;
                        $content->vendor_engine = $sd_model;
                        $content->save();

                        $image_result = $this->createImageBox($content);
                        array_push($results, $image_result);

                    }

                    # Update credit balance
                    $this->updateBalance($max_results);

                    $data['status'] = 'success';
                    $data['images'] = $results;
                    $data['old'] = auth()->user()->available_images + auth()->user()->available_images_prepaid;
                    $data['current'] = auth()->user()->available_images + auth()->user()->available_images_prepaid - $max_results;
                    return $data; 

                } else {

                    $data['status'] = 'error';
                    $data['message'] = __('There was an issue generating your AI Image, please try again or contact support team');
                    return $data;
                }

            }
           

        }
	}


    /**
	*
	* Update user image balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalance($images) {

        $user = User::find(Auth::user()->id);

        if (auth()->user()->available_images != -1) {
        
            if (Auth::user()->available_images > $images) {

                $total_images = Auth::user()->available_images - $images;
                $user->available_images = ($total_images < 0) ? 0 : $total_images;

            } elseif (Auth::user()->available_images_prepaid > $images) {

                $total_images_prepaid = Auth::user()->available_images_prepaid - $images;
                $user->available_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;

            } elseif ((Auth::user()->available_images + Auth::user()->available_images_prepaid) == $images) {

                $user->available_images = 0;
                $user->available_images_prepaid = 0;

            } else {

                if (!is_null(Auth::user()->member_of)) {

                    $member = User::where('id', Auth::user()->member_of)->first();

                    if ($member->available_images > $images) {

                        $total_images = $member->available_images - $images;
                        $member->available_images = ($total_images < 0) ? 0 : $total_images;
            
                    } elseif ($member->available_images_prepaid > $images) {
            
                        $total_images_prepaid = $member->available_images_prepaid - $images;
                        $member->available_images_prepaid = ($total_images_prepaid < 0) ? 0 : $total_images_prepaid;
            
                    } elseif (($member->available_images + $member->available_images_prepaid) == $images) {
            
                        $member->available_images = 0;
                        $member->available_images_prepaid = 0;
            
                    } else {
                        $remaining = $images - $member->available_images;
                        $member->available_images = 0;
        
                        $prepaid_left = $member->available_images_prepaid - $remaining;
                        $member->available_images_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    }

                    $member->update();

                } else {
                    $remaining = $images - Auth::user()->available_images;
                    $user->available_images = 0;

                    $prepaid_left = Auth::user()->available_images_prepaid - $remaining;
                    $user->available_images_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                }
            }
        }

        $user->update();

    }


     /**
	*
	* Process media file
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function view(Request $request) 
    {
        if ($request->ajax()) {

            $image = Image::where('id', request('id'))->first(); 

            if ($image) {
                if ($image->user_id == Auth::user()->id){

                    $image_url = ($image->storage == 'local') ? URL::asset($image->image) : $image->image;
                    $image_vendor = ($image->vendor == 'sd') ? __('Stable Diffusion') : __('Dalle');
                    $image_url_second = url($image->image);
                    $image_style = ($image->image_style == 'none') ? __('Not Set') : ucfirst($image->image_style);
                    $image_lighting = ($image->image_lighting == 'none') ? __('Not Set') : ucfirst($image->image_lighting);
                    $image_medium = ($image->image_medium == 'none') ? __('Not Set') : ucfirst($image->image_medium);
                    $image_mood = ($image->image_mood == 'none') ? __('Not Set') : ucfirst($image->image_mood);
                    $image_artist = ($image->image_artist == 'none') ? __('Not Set') : ucfirst($image->image_artist);

                    if (!is_null($image->negative_prompt)) {
                        $image_negative_prompt = ' <div class="row mt-5">
                                                    <div class="col-sm-12">
                                                        <h6 class="mb-3 description-title">'. __('Negative Prompt') .'</h6>
                                                        <a href="#" class="copy-image-negative-prompt"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
                                                        <div class="image-prompt" id="image-negative-prompt-text">
                                                            <p>'. $image->negative_prompt.'</p>
                                                        </div>
                                                    </div>
                                                </div>';
                    } else {
                        $image_negative_prompt = '';
                    }

                    if (is_null($image->vendor_engine)) {
                        $image_engine = __('Not Set');
                    } else {
                        switch ($image->vendor_engine) {
                            case 'dall-e-2': $image_engine = 'Dalle 2'; break;
                            case 'dall-e-3': $image_engine = 'Dalle 3'; break;
                            case 'dall-e-3-hd': $image_engine = 'Dalle 3 HD'; break;
                            case 'stable-diffusion-v1-5': $image_engine = 'Stable Diffusion v1.5'; break;
                            case 'stable-diffusion-512-v2-1': $image_engine = 'Stable Diffusion v2.1'; break;
                            case 'stable-diffusion-768-v2-1': $image_engine = 'Stable Diffusion v2.1-768'; break;
                            case 'stable-diffusion-xl-beta-v2-2-2': $image_engine = 'Stable Diffusion v2.2.2-XL Beta'; break;
                            case 'stable-diffusion-xl-1024-v0-9': $image_engine = 'SDXL v0.9'; break;
                            case 'stable-diffusion-xl-1024-v1-0': $image_engine = 'SDXL v1.0'; break;
                            default: $image_engine = 'Dalle 2';
                        }
                    }

                    $data['status'] = 'success';
                    $data['modal'] = '<div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 image-view-outer">
                                            <div class="image-view-box text-center">
                                                <a href="'. $image_url_second .'" class="download-image text-center" download><i class="fa-sharp fa-solid fa-arrow-down-to-line" title="' .__('Download Image') .'"></i></a>
                                                <img src="'. $image_url .'" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="image-description-box">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Created')
                                                        .'</div>
                                                        <div class="description-data">
                                                            ' . date_format($image->created_at, 'F d, Y') . '
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('AI Vendor')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image_vendor
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('AI Vendor Engine')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image_engine
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Resolution')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image->resolution
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Image Style')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image_style
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Lighting Style')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image_lighting
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Image Medium')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image_medium
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Artist Name')
                                                       .'</div>
                                                        <div class="description-data">'.
                                                            $image_artist
                                                        .'</div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 mb-5">
                                                        <div class="description-title">'.
                                                             __('Image Mood')
                                                        .'</div>
                                                        <div class="description-data">'.
                                                            $image_mood
                                                        .'</div>
                                                    </div>
                                                </div>
                                                <div class="row mt-5">
                                                    <div class="col-sm-12">
                                                        <h6 class="mb-3 description-title">'. __('Image Prompt') .'</h6>
                                                        <a href="#" class="copy-image-prompt"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" fill="currentColor" width="20"> <path d="M180 975q-24 0-42-18t-18-42V312h60v603h474v60H180Zm120-120q-24 0-42-18t-18-42V235q0-24 18-42t42-18h440q24 0 42 18t18 42v560q0 24-18 42t-42 18H300Zm0-60h440V235H300v560Zm0 0V235v560Z"></path> </svg></a>
                                                        <div class="image-prompt" id="image-prompt-text">
                                                            <p>'. $image->description.'</p>
                                                        </div>
                                                    </div>
                                                </div>'
                                                . $image_negative_prompt.
                                            '</div>
                                        </div>
                                    </div>';
                    return $data;  
        
                } else{
    
                    $data['status'] = 'error';
                    $data['message'] = __('There was an error while retrieving this image');
                    return $data;
                }  
            } else {
                $data['status'] = 'error';
                $data['message'] = __('Image was not found');
                return $data;
            }
            
        }
	}


    /**
	*
	* Delete File
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function delete(Request $request) 
    {
        if ($request->ajax()) {

            $verify = $this->user->verify_license();
            if($verify['status']!=true){return false;}

            $image = Image::where('id', request('id'))->first(); 

            if ($image->user_id == auth()->user()->id){

                switch ($image->storage) {
                    case 'local':
                        if (Storage::exists($image->image)) {
                            Storage::delete($image->image);
                        }
                        break;
                    case 'aws':
                        if (Storage::disk('s3')->exists($image->image_name)) {
                            Storage::disk('s3')->delete($image->image_name);
                        }
                        break;
                    case 'wasabi':
                        if (Storage::disk('wasabi')->exists($image->image_name)) {
                            Storage::disk('wasabi')->delete($image->image_name);
                        }
                        break;
                    default:
                        # code...
                        break;
                }

                $image->delete();

                $data['status'] = 'success';
                return $data;  
    
            } else{

                $data['status'] = 'error';
                $data['message'] = __('There was an error while deleting the image');
                return $data;
            }  
        }
	}


    /**
	*
	* Load More Images
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function loadMore(Request $request) 
    {
        $start = $request->start;
 
        $images = Image::where('user_id', Auth::user()->id)->latest()->skip($start)->limit(6)->get();
 
        $html = "";
        foreach($images as $image){

            $image_url = ($image->storage == 'local') ? URL::asset($image->image) : $image->image;
            $image_vendor = ($image->vendor == 'sd') ? __('Stable Diffusion') : __('Dalle');
            $image_description = substr($image->description, 0, 63);
            $image_url_second = url($image->image);
            $image_id = $image->id;
            
            $html .= '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 image-container">				
					    <div class="grid-item">
						    <div class="grid-image-wrapper">
                                <div class="flex grid-buttons text-center">
                                    <a href="'. $image_url_second .'" class="grid-image-view text-center" download><i class="fa-sharp fa-solid fa-arrow-down-to-line" title="'. __("Download Image") .'"></i></a>
                                    <a href="#" class="grid-image-view text-center viewImageResult" id="'. $image_id .'"><i class="fa-sharp fa-solid fa-camera-viewfinder" title="'. __("View Image") .'"></i></a>
                                    <a href="#" class="grid-image-view text-center deleteResultButton" id="'. $image_id .'"><i class="fa-solid fa-trash-xmark" title="'. __("Delete Image") .'"></i></a>							
                                </div>
                                <a href="#">
                                    <span class="grid-image">
                                        <img class="loaded" src="' . $image_url . '" alt="" >
                                    </span>
                                </a>
                                <div class="grid-description">
                                    <span class="fs-9 text-primary">' . $image_vendor . '</span>
                                    <p class="fs-10 mb-0">' . $image_description . '...</p>
                                </div>
						    </div>
                        </div>
					</div>';
        }
 
        $data['html'] = $html;

        return response()->json($data);
	}


    public function createImageBox($image) 
    {
        $image_url = ($image->storage == 'local') ? URL::asset($image->image) : $image->image;
        $image_vendor = ($image->vendor == 'sd') ? __('Stable Diffusion') : __('Dalle');
        $image_description = substr($image->description, 0, 63);
        $image_url = url($image->image);
        $image_id = $image->id;

        return '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 image-container">				
                    <div class="grid-item">
                        <div class="grid-image-wrapper">
                            <div class="flex grid-buttons text-center">
                                <a href="'. $image_url .'" class="grid-image-view text-center" download><i class="fa-sharp fa-solid fa-arrow-down-to-line" title="'. __("Download Image") .'"></i></a>
                                <a href="#" class="grid-image-view text-center viewImageResult" id="'. $image_id .'"e><i class="fa-sharp fa-solid fa-camera-viewfinder" title="'. __("View Image") .'"></i></a>
                                <a href="#" class="grid-image-view text-center deleteResultButton" id="'. $image_id .'"><i class="fa-solid fa-trash-xmark" title="'. __("Delete Image") .'"></i></a>							
                            </div>
                            <a href="#">
                                <span class="grid-image">
                                    <img class="loaded" src="' . $image_url . '" alt="" >
                                </span>
                            </a>
                            <div class="grid-description">
                                <span class="fs-9 text-primary">' . $image_vendor . '</span>
                                <p class="fs-10 mb-0">' . $image_description . '...</p>
                            </div>
                        </div>
                    </div>
                </div>';
    }
    


}
