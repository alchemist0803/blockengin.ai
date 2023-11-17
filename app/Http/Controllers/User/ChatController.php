<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Orhanerday\OpenAi\OpenAi;
use App\Models\SubscriptionPlan;
use App\Models\FavoriteChat;
use App\Models\ChatConversation;
use App\Models\ChatCategory;
use App\Models\ChatHistory;
use App\Models\ChatPrompt;
use App\Models\ApiKey;
use App\Models\Chat;
use App\Models\User;


class ChatController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if (session()->has('message_code')) {
            session()->forget('message_code');
        }

        $favorite_chats = Chat::select('chats.*', 'favorite_chats.*')->where('favorite_chats.user_id', auth()->user()->id)->join('favorite_chats', 'favorite_chats.chat_code', '=', 'chats.chat_code')->where('status', true)->orderBy('category', 'asc')->get();    
        $user_chats = FavoriteChat::where('user_id', auth()->user()->id)->pluck('chat_code');     
        $other_chats = Chat::whereNotIn('chat_code', $user_chats)->where('status', true)->orderBy('category', 'asc')->get();  
        $chat_categories = Chat::where('status', true)->groupBy('group')->pluck('group'); 
        $categories = ChatCategory::whereIn('code', $chat_categories)->orderBy('name', 'asc')->get();                  
        
        return view('user.chat.index', compact('favorite_chats', 'other_chats', 'categories'));
    }


    /**
	*
	* Process Input Text
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function process(Request $request) 
    {       
        # Check if user has access to the chat bot
        $template = Chat::where('chat_code', $request->chat_code)->first();
        if (auth()->user()->group == 'user') {
            if (config('settings.chat_feature_user') == 'allow') {
                if (config('settings.chats_access_user') != 'all' && config('settings.chats_access_user') != 'premium') {
                    if (is_null(auth()->user()->member_of)) {
                        if ($template->category == 'professional' && config('settings.chats_access_user') != 'professional') {                       
                            $data['status'] = 'error';
                            $data['message'] = __('This Ai chat assistant is not available for your account, subscribe to get a proper access');
                            return $data;                        
                        } else if($template->category == 'premium' && (config('settings.chats_access_user') != 'premium' && config('settings.chats_access_user') != 'all')) {
                            $data['status'] = 'error';
                            $data['message'] = __('This Ai chat assistant is not available for your account, subscribe to get a proper access');
                            return $data;
                        } else if(($template->category == 'standard' || $template->category == 'all') && (config('settings.chats_access_user') != 'professional' && config('settings.chats_access_user') != 'standard')) {
                            $data['status'] = 'error';
                            $data['message'] = __('This Ai chat assistant is not available for your account, subscribe to get a proper access');
                            return $data;
                        }

                    } else {
                        $user = User::where('id', auth()->user()->member_of)->first();
                        $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
                        if ($plan->chats != 'all' && $plan->chats != 'premium') {          
                            if ($template->category == 'premium' && ($plan->chats != 'premium' && $plan->chats != 'all')) {
                                $status = 'error';
                                $message =  __('Your team subscription does not include support for this chat assistant category');
                                return response()->json(['status' => $status, 'message' => $message]); 
                            } else if(($template->category == 'standard' || $template->category == 'all') && ($plan->chats != 'standard' && $plan->chats != 'all')) {
                                $status = 'error';
                                $message =  __('Your team subscription does not include support for this chat assistant category');
                                return response()->json(['status' => $status, 'message' => $message]); 
                            } else if($template->category == 'professional' && $plan->chats != 'professional') {
                                $status = 'error';
                                $message =  __('Your team subscription does not include support for this chat assistant category');
                                return response()->json(['status' => $status, 'message' => $message]); 
                            }                  
                        }
                    }
                    
                }                
            } else {
                if (is_null(auth()->user()->member_of)) {
                    $status = 'error';
                    $message = __('Ai chat assistant feature is not available for free tier users, subscribe to get a proper access');
                    return response()->json(['status' => $status, 'message' => $message]);
                } else {
                    $user = User::where('id', auth()->user()->member_of)->first();
                    $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
                    if ($plan->chats != 'all' && $plan->chats != 'premium') {          
                        if ($template->category == 'premium' && ($plan->chats != 'premium' && $plan->chats != 'all')) {
                            $status = 'error';
                            $message =  __('Your team subscription does not include support for this chat assistant category');
                            return response()->json(['status' => $status, 'message' => $message]); 
                        } else if(($template->category == 'standard' || $template->category == 'all') && ($plan->chats != 'standard' && $plan->chats != 'all')) {
                            $status = 'error';
                            $message =  __('Your team subscription does not include support for this chat assistant category');
                            return response()->json(['status' => $status, 'message' => $message]); 
                        } else if($template->category == 'professional' && $plan->chats != 'professional') {
                            $status = 'error';
                            $message =  __('Your team subscription does not include support for this chat assistant category');
                            return response()->json(['status' => $status, 'message' => $message]); 
                        }                  
                    }
                }                      
            }
        } elseif (auth()->user()->group == 'subscriber') {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($plan->chats != 'all' && $plan->chats != 'premium') {     
                if ($template->category == 'premium' && ($plan->chats != 'premium' && $plan->chats != 'all')) {
                    $status = 'error';
                    $message =  __('Your current subscription does not include support for this chat assistant category');
                    return response()->json(['status' => $status, 'message' => $message]); 
                } else if(($template->category == 'standard' || $template->category == 'all') && ($plan->chats != 'standard' && $plan->chats != 'all')) {
                    $status = 'error';
                    $message =  __('Your current subscription does not include support for this chat assistant category');
                    return response()->json(['status' => $status, 'message' => $message]); 
                } else if($template->category == 'professional' && $plan->chats != 'professional') {
                    $status = 'error';
                    $message =  __('Your current subscription does not include support for this chat assistant category');
                    return response()->json(['status' => $status, 'message' => $message]); 
                }                   
            }
        }


        # Check personal API keys
        if (config('settings.personal_openai_api') == 'allow') {
            if (is_null(auth()->user()->personal_openai_key)) {
                $status = 'error';
                $message =  __('You must include your personal Openai API key in your profile settings first');
                return response()->json(['status' => $status, 'message' => $message]); 
            }     
        } elseif (!is_null(auth()->user()->plan_id)) {
            $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
            if ($check_api->personal_openai_api) {
                if (is_null(auth()->user()->personal_openai_key)) {
                    $status = 'error';
                    $message =  __('You must include your personal Openai API key in your profile settings first');
                    return response()->json(['status' => $status, 'message' => $message]); 
                } 
            }    
        } 


        # Check if user has sufficient words available to proceed
        if (auth()->user()->available_words != -1) {
            $balance = auth()->user()->available_words + auth()->user()->available_words_prepaid;
            $words = count(explode(' ', ($request->input('message'))));
            if ((auth()->user()->available_words + auth()->user()->available_words_prepaid) < $words) {
                if (!is_null(auth()->user()->member_of)) {
                    if (auth()->user()->member_use_credits_chat) {
                        $member = User::where('id', auth()->user()->member_of)->first();
                        if (($member->available_words + $member->available_words_prepaid) < $words) {
                            $status = 'error';
                            $message = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                            return response()->json(['status' => $status, 'message' => $message]);
                        }
                    } else {
                        $status = 'error';
                        $message = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                        return response()->json(['status' => $status, 'message' => $message]);
                    }
                
                } else {
                    $status = 'error';
                    $message = __('Not enough word balance to proceed, subscribe or top up your word balance and try again');
                    return response()->json(['status' => $status, 'message' => $message]);
                } 
            }
        }

        $uploading = new UserService();
        $upload = $uploading->prompt();
        if (!$upload['status']) return;

        $chat = new ChatHistory();
        $chat->user_id = auth()->user()->id;
        $chat->conversation_id = $request->conversation_id;
        $chat->prompt = $request->input('message');
        $chat->save();
        

        session()->put('conversation_id', $request->conversation_id);
        session()->put('chat_id', $chat->id);

        if (auth()->user()->available_words != -1) {
            return response()->json(['status' => 'success', 'old'=> $balance, 'current' => ($balance - $words), 'chat_id' => $chat->id]);
        } else {
            return response()->json(['status' => 'success', 'old'=> 0, 'current' => 0, 'chat_id' => $chat->id]);
        }

	}


     /**
	*
	* Process Chat
	* @param - file id in DB
	* @return - confirmation
	*
	*/
    public function generateChat(Request $request) 
    {  
        $conversation_id = $request->conversation_id;

        return response()->stream(function () use($conversation_id) {

            if (config('settings.personal_openai_api') == 'allow') {
                $open_ai = new OpenAi(auth()->user()->personal_openai_key);        
            } elseif (!is_null(auth()->user()->plan_id)) {
                $check_api = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                if ($check_api->personal_openai_api) {
                    $open_ai = new OpenAi(auth()->user()->personal_openai_key);               
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
    
            if (session()->has('chat_id')) {
                $chat_id = session()->get('chat_id');
            }

            $chat_conversation = ChatConversation::where('conversation_id', $conversation_id)->first();
            $main_chat = Chat::where('chat_code', $chat_conversation->chat_code)->first();
            $chat_message = ChatHistory::where('conversation_id', $conversation_id)->orderBy('created_at', 'desc')->take(6)->get()->reverse();

            $messages[] = ['role' => 'system', 'content' => $main_chat->prompt];
            foreach ($chat_message as $chat) {
                $messages[] = ['role' => 'user', 'content' => $chat['prompt']];
                if (!empty($chat['response'])) {
                    $messages[] = ['role' => 'assistant', 'content' => $chat['response']];
                }
            }

            $text = "";

            # Apply proper model based on role and subsciption
            if (auth()->user()->group == 'user') {
                $model = config('settings.default_model_user');
            } elseif (auth()->user()->group == 'admin') {
                $model = config('settings.default_model_admin');
            } else {
                $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->first();
                $model = $plan->model_chat;
            } 

            if ($model == 'gpt-3.5-turbo' || $model == 'gpt-3.5-turbo-16k' || $model == 'gpt-4' || $model == 'gpt-4-32k' || $model == 'gpt-4-1106-preview' || $model == 'gpt-4-vision-preview') {
                $opts = [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 1.0,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'stream' => true
                ];

            } else {
                $opts = [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $messages,
                    'temperature' => 1.0,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                    'stream' => true
                ];
            }
            

            $complete = $open_ai->chat($opts, function ($curl_info, $data) use (&$text) {
                if ($obj = json_decode($data) and $obj->error->message != "") {
                    error_log(json_encode($obj->error->message));
                } else {
                    echo $data;

                    $array = explode('data: ', $data);
                    foreach ($array as $response){
                        $response = json_decode($response, true);
                        if ($data != "data: [DONE]\n\n" and isset($response["choices"][0]["delta"]["content"])) {
                            $text .= $response["choices"][0]["delta"]["content"];
                        }
                    }
                }

                echo PHP_EOL;
                ob_flush();
                flush();
                return strlen($data);
            });

            # Update credit balance
            $words = count(explode(' ', ($text)));
            $this->updateBalance($words);  

            $current_chat = ChatHistory::where('id', $chat_id)->first();
            $current_chat->response = $text;
            $current_chat->words = $words;
            $current_chat->save();

            $chat_conversation->words = ++$words;
            $chat_conversation->messages = $chat_conversation->messages + 1;
            $chat_conversation->save();

        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
        
    }


    /**
	*
	* Clear Session
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function clear(Request $request) 
    {
        if (session()->has('conversation_id')) {
            session()->forget('conversation_id');
        }

        return response()->json(['status' => 'success']);
	}



    /**
	*
	* Update user word balance
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function updateBalance($words) {

        $user = User::find(Auth::user()->id);

        if (auth()->user()->available_words != -1) {
        
            if (Auth::user()->available_words > $words) {

                $total_words = Auth::user()->available_words - $words;
                $user->available_words = ($total_words < 0) ? 0 : $total_words;
                $user->update();

            } elseif (Auth::user()->available_words_prepaid > $words) {

                $total_words_prepaid = Auth::user()->available_words_prepaid - $words;
                $user->available_words_prepaid = ($total_words_prepaid < 0) ? 0 : $total_words_prepaid;
                $user->update();

            } elseif ((Auth::user()->available_words + Auth::user()->available_words_prepaid) == $words) {

                $user->available_words = 0;
                $user->available_words_prepaid = 0;
                $user->update();

            } else {

                if (!is_null(Auth::user()->member_of)) {

                    $member = User::where('id', Auth::user()->member_of)->first();

                    if ($member->available_words > $words) {

                        $total_words = $member->available_words - $words;
                        $member->available_words = ($total_words < 0) ? 0 : $total_words;
            
                    } elseif ($member->available_words_prepaid > $words) {
            
                        $total_words_prepaid = $member->available_words_prepaid - $words;
                        $member->available_words_prepaid = ($total_words_prepaid < 0) ? 0 : $total_words_prepaid;
            
                    } elseif (($member->available_words + $member->available_words_prepaid) == $words) {
            
                        $member->available_words = 0;
                        $member->available_words_prepaid = 0;
            
                    } else {
                        $remaining = $words - $member->available_words;
                        $member->available_words = 0;
        
                        $prepaid_left = $member->available_words_prepaid - $remaining;
                        $member->available_words_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    }

                    $member->update();

                } else {
                    $remaining = $words - Auth::user()->available_words;
                    $user->available_words = 0;

                    $prepaid_left = Auth::user()->available_words_prepaid - $remaining;
                    $user->available_words_prepaid = ($prepaid_left < 0) ? 0 : $prepaid_left;
                    $user->update();
                }  
            }
        }

        return true;
    }


    /**
	*
	* Chat conversation
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function conversation(Request $request) {

        if ($request->ajax()) {

            $chat = new ChatConversation();
            $chat->user_id = auth()->user()->id;
            $chat->title = 'New Conversation';
            $chat->chat_code = $request->chat_code;
            $chat->conversation_id = $request->conversation_id;
            $chat->messages = 0;
            $chat->words = 0;
            $chat->save();

            $data = 'success';
            return $data;
        }   
    }


    /**
	*
	* Chat history
	* @param - total words generated
	* @return - confirmation
	*
	*/
    public function history(Request $request) {

        if ($request->ajax()) {

            $messages = ChatHistory::where('user_id', auth()->user()->id)->where('conversation_id', $request->conversation_id)->get();
            return $messages;
        }   
    }


    /**
	* 
	* Process media file
	* @param - file id in DB
	* @return - confirmation
	* 
	*/
	public function view($code) 
    {
        if (session()->has('conversation_id')) {
            session()->forget('conversation_id');
        }

        $chat = Chat::where('chat_code', $code)->first(); 
        $messages = ChatConversation::where('user_id', auth()->user()->id)->where('chat_code', $chat->chat_code)->orderBy('updated_at', 'desc')->get(); 

        $categories = ChatPrompt::where('status', true)->groupBy('group')->pluck('group'); 
        $prompts = ChatPrompt::all();

        return view('user.chat.view', compact('chat', 'messages', 'categories', 'prompts'));
	}


    /**
	*
	* Rename conversation
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function rename(Request $request) 
    {
        if ($request->ajax()) {

            $chat = ChatConversation::where('conversation_id', request('conversation_id'))->first(); 

            if ($chat) {
                if ($chat->user_id == auth()->user()->id){

                    $chat->title = request('name');
                    $chat->save();
    
                    $data['status'] = 'success';
                    $data['conversation_id'] = request('conversation_id');
                    return $data;  
        
                } else{
    
                    $data['status'] = 'error';
                    $data['message'] = __('There was an error while changing the conversation title');
                    return $data;
                }
            } 
              
        }
	}


    /**
	*
	* Delete chat
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function delete(Request $request) 
    {
        if ($request->ajax()) {

            $chat = ChatConversation::where('conversation_id', request('conversation_id'))->first(); 

            if ($chat) {
                if ($chat->user_id == auth()->user()->id){

                    $chat->delete();

                    if (session()->has('conversation_id')) {
                        session()->forget('conversation_id');
                    }
    
                    $data['status'] = 'success';
                    return $data;  
        
                } else{
    
                    $data['status'] = 'error';
                    $data['message'] = __('There was an error while deleting the chat history');
                    return $data;
                }
            } else {
                $data['status'] = 'empty';
                return $data;
            }
              
        }
	}


     /**
	*
	* Set favorite status
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function favorite(Request $request) 
    {
        if ($request->ajax()) {


            $chat = Chat::where('chat_code', request('id'))->first(); 

            $favorite = FavoriteChat::where('chat_code', $chat->chat_code)->where('user_id', auth()->user()->id)->first();

            if ($favorite) {

                $favorite->delete();

                $data['status'] = 'success';
                $data['set'] = true;
                return $data;  
    
            } else{

                $new_favorite = new FavoriteChat();
                $new_favorite->user_id = auth()->user()->id;
                $new_favorite->chat_code = $chat->chat_code;
                $new_favorite->save();

                $data['status'] = 'success';
                $data['set'] = false;
                return $data; 
            }  
        }
	}


    public function escapeJson($value) 
    { 
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }

}
