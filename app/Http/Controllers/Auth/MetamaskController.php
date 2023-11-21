<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\User;

use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

use Spatie\Permission\Traits\HasRoles;

class MetamaskController extends Controller
{

    protected function user()
    {
        $user = null;
        if (Auth::user()) {
            $user = User::where('id', Auth::user()->id)->first();
        }

        return $user;
    }


    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request)
    {

        $user = User::where('metamask_address', $request->address)->first();

        if($user) {

            // toastr()->success(__('Congratulation! You are logged in with Metamask!'));

        } else{
            
            $this->createNewUser($request);
            // toastr()->success(__('Congratulation! You can now proceed to login with your Metamask!'));
       } 

       if (config('services.google.recaptcha.enable') == 'on') {

            $recaptchaResult = $this->reCaptchaCheck(request('recaptcha'));

            if ($recaptchaResult->success != true) {
                // return redirect()->back()->with('error', 'Google reCaptcha Validation has Failed');
                return response()->json([
                    'message' => 'Success'
                ], 200);

            }

            if ($recaptchaResult->score >= 0.5) {

                $request->authenticate();
                
                if (Auth::check() && auth()->user()->google2fa_enabled && !$request->session()->has('2fa')) {
            
                    // return redirect()->route('login.2fa');
                    return response()->json([
                        'message' => 'Success'
                    ], 200);
    
                } else {

                    if (auth()->user()->hasRole('admin')) {
                        
                        $request->session()->regenerate();

                        // return redirect()->route('admin.dashboard');            
                        return response()->json([
                            'message' => 'Success'
                        ], 200);
                    }
                    
                    if (config('frontend.maintenance') == 'on') {

                        if (auth()->user()->group != 'admin') {
                            // return redirect('/')->with(Auth::logout());            
                            return response()->json([
                                'message' => 'Success'
                            ], 200);
                        }

                    } else{

                        $request->session()->regenerate();

                        // return redirect()->intended(RouteServiceProvider::HOME);
                        return response()->json([
                            'message' => 'Success'
                        ], 200);
                    }
                }
                  

            } else {
                // return redirect()->back()->with('error', 'Google reCaptcha Validation has Failed');
                return response()->json([
                    'message' => 'Success'
                ], 200);
            }

        } else {
            
            $request->authenticate();

            if (Auth::check() && auth()->user()->google2fa_enabled && !$request->session()->has('2fa')) {
            
                // return redirect()->route('login.2fa');
                return response()->json([
                    'message' => 'Success'
                ], 200);

            } else {

                if (auth()->user()->hasRole('admin')) {

                    $request->session()->regenerate();
    
                    // return redirect()->route('admin.dashboard');            
                    return response()->json([
                        'message' => 'Success'
                    ], 200);
                }
            
                if (config('frontend.maintenance') == 'on') {

                    if (auth()->user()->group != 'admin') {
                        // return redirect('/')->with(Auth::logout());          
                        return response()->json([
                            'message' => 'Success'
                        ], 200);
                    }
    
                } else{
    
                    $request->session()->regenerate();
    
                    // return redirect()->intended(RouteServiceProvider::HOME);
                    return response()->json([
                        'message' => 'Success'
                    ], 200);
                }
            }
        }
    }

    /**
     * Create new user
     * 
     */
    public function createNewUser(Request $request)
    {
        $user = User::create([
            'name' => $request->address,
            'email' => $request->address . '@metamask.com',
            'metamask_address' => $request->address,
            'password' => Hash::make($request->password),
        ]);
        
        event(new Registered($user));

        $referral_code = ($request->hasCookie('referral')) ? $request->cookie('referral') : ''; 
        $referrer = ($referral_code != '') ? User::where('referral_id', $referral_code)->firstOrFail() : '';
        $referrer_id = ($referrer != '') ? $referrer->id : '';

        $status = (config('settings.email_verification') == 'disabled') ? 'active' : 'pending';
        
        $user->assignRole(config('settings.default_user'));
        $user->status = $status;
        $user->group = config('settings.default_user');
        $user->available_words = config('settings.free_tier_words');
        $user->available_images = config('settings.free_tier_images');
        $user->available_chars = config('settings.voiceover_welcome_chars');
        $user->available_minutes = config('settings.whisper_welcome_minutes');
        $user->default_voiceover_language = config('settings.voiceover_default_language');
        $user->default_voiceover_voice = config('settings.voiceover_default_voice');
        $user->default_template_language = config('settings.default_language');
        $user->job_role = 'Happy Person';
        $user->referral_id = strtoupper(Str::random(15));
        $user->referred_by = $referrer_id;
        $user->save();      
     
    }

}
