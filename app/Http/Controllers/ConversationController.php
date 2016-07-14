<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\Conversation_user;
use App\Conversation;
use App\Conversation_message;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Request;
use LRedis;
 

class ConversationController extends Controller
{
    
    public function create()
    {
        
        if(Request::ajax()) 
        {
            $friend_id = Input::get('id');
            $friend = User::where('id',Input::get('id'))->first();
            $user = Auth::user();
            $exist = false;

            foreach($user->conversations as $my_conversation_users)
            {
                if($my_conversation_users->conversation)
                {             
                        if(count($my_conversation_users->conversation->conversation_users)==2)
                        {
                            foreach($my_conversation_users->conversation->conversation_users as $my_conversation_friend)
                            {
                                if($my_conversation_friend->user_id == $friend_id)
                                {
                                    $my_conversation_friends = $my_conversation_friend;
                                    $exist = true;
                                    $conv =  $my_conversation_friend->conversation;
                                    break;
                                }
                            }
                        }
                }
            }

            
            if($exist)
            {
                $users = array();
                foreach($conv->conversation_users as $conv_user)
                {
                    array_push($users,$conv_user->user);
                }
                $messages = $conv->conversation_messages;
            }
            else
            {
                $conv = new Conversation;
                $conv->name = $user->firstname.' & '.$friend->firstname;
                $conv->save();
                
                $conversation_user_me = new Conversation_user;
                $conversation_user_me->conversation_id = $conv->id;
                $conversation_user_me->user_id = $user->id;
                $conversation_user_me->save();
                
                $conversation_user_friend = new Conversation_user;
                $conversation_user_friend->conversation_id = $conv->id;
                $conversation_user_friend->user_id = $friend_id;
                $conversation_user_friend->save();
                
                $users = array();
                array_push($users,$conversation_user_me);
                array_push($users,$conversation_user_friend);
                
                $messages = array();
            }

            return \Response::json(array(
                    'success' => true,
                    'conv' => $conv,
                    'users' => $users,
                    'messages' => $messages,
                ));
        }
    }
    
    public function show()
    {
        
        if(Request::ajax()) 
        {
            if(!empty(Input::get('conv_id')))
            {
                
                $conv = Conversation::where('id',Input::get('conv_id'))->first();
                 return \Response::json(array(
                    'success' => true,
                    'conv' => $conv,
                    'users' => $conv->conversation_users,
                    'messages' => $conv->conversation_messages,
                ));
            } 
        }
    }
    
    public function sendMessage()
    {
        if(Request::ajax()) 
        {
            if(!empty(Input::get('conversation_id')) && !empty(Input::get('message')))
            {
                $conv = new Conversation();
                $conv = $conv::where('id',Input::get('conversation_id'));
                
                if($conv->first())
                {
                    $user = Auth::user();
                    
                    $conv_message = new Conversation_message();
                    $conv_message->conversation_id = Input::get('conversation_id');
                    $conv_message->message = Input::get('message');
                    $conv_message->user_id = $user->id;
                    $conv_message->save();
                    
                    $redis = LRedis::connection();
                    $redis->publish('message', json_encode(['message'=>$conv_message->message,
                                                            'conv_id'=>Input::get('conversation_id'),
                                                            'user'=>$user,
                                                            'users'=>$conv->first()->conversation_users
                                                           ]));

                    return \Response::json(array(
                            'success' => true,
                        'conv_id' => Input::get('conversation_id'),
                        ));
                }
                
            }

        }
	}
    
    function changeName()
    {
        if(Request::ajax()) 
        {
            if(!empty(Input::get('conversation_id')) && !empty(Input::get('conv_name')))
            {
                $conv = new Conversation();
                $conv = $conv::where('id',Input::get('conversation_id'));
                
                if($conv->first())
                {
                    $conv->update(['name' => Input::get('conv_name')]);
                    
                    $conv = Conversation::where('id',Input::get('conversation_id'))->first();
                    
                    $redis = LRedis::connection();
                    $redis->publish('change_name', json_encode(['conv_name'=>Input::get('conv_name'),
                                                                'conv_id'=>Input::get('conversation_id'),
                                                                'user'=>Auth::user(),
                                                                'users'=>$conv->conversation_users
                                                                ]));
                }
                
            }

        }
	}
    function showUser()
    {
        if(Request::ajax()) 
        {
            if(!empty(Input::get('conversation_id')) && !empty(Input::get('add_user')))
            {
                $conv = new Conversation();
                $conv = $conv::where('id',Input::get('conversation_id'));
                
                if($conv->first())
                {
                    
                    $conv_users = $conv->first()->conversation_users;  
                    $friends = Auth::user()->friends;
                    
                    foreach($friends as $friendKey => $friend)
                    {
                        $total_name = $friend->firstname.' '.$friend->lastname;
                        if(stripos($total_name,Input::get('add_user')) !== false)
                        {
                            foreach($conv_users as $conv_user)
                            {
                                if($friend->id == $conv_user->user_id)
                                {
                                    unset($friends[$friendKey]);
                                }
                            }
                            
                        }
                        else
                        {
                           unset($friends[$friendKey]); 
                        }
                    }
                    return \Response::json(array(
                            'success' => true,
                        'friends' => $friends,
                        ));
                }
                
            }

        }
	}
    
    function addUser()
    {
        if(Request::ajax()) 
        {
            $friend = User::where('id',Input::get('friend_id'))->first();
            if(!empty(Input::get('add_user')) && !empty(Input::get('friend_id')))
            {
                $conv = new Conversation();
                $conv = $conv::where('id',Input::get('conversation_id'))->first();
                
                if($conv)
                {
                    if($conv->group == false)
                    {
                        $new_conv = new Conversation;
                        $new_conv->name = $conv->name.' & '.$friend->firstname;
                        $new_conv->group = true;
                        $new_conv->save();

                        foreach($conv->conversation_users as $conversation_user)
                        {
                            $new_conversation_user = new Conversation_user;
                            $new_conversation_user->conversation_id = $new_conv->id;
                            $new_conversation_user->user_id = $conversation_user->user_id;
                            $new_conversation_user->save();
                        }

                        $new_conversation_user = new Conversation_user;
                        $new_conversation_user->conversation_id = $new_conv->id;
                        $new_conversation_user->user_id = Input::get('friend_id');
                        $new_conversation_user->save();
                        
                        $real_conv = $new_conv;
                    }
                    else
                    {
                        $new_conversation_user = new Conversation_user;
                        $new_conversation_user->conversation_id = $conv->id;
                        $new_conversation_user->user_id = Input::get('friend_id');
                        $new_conversation_user->save();
                        
                        $real_conv = $conv;
                    }
                    
                    $conv_id = $real_conv->id;
                    $conv_users = $real_conv->conversation_users;
                    $messages = $real_conv->conversation_messages;
                   
                    $redis = LRedis::connection();
                    $redis->publish('add_user', json_encode(['friend'=>$friend,
                                                             'conv_id'=>$conv_id,
                                                             'user'=>Auth::user(),
                                                             'users'=>$conv_users
                                                            ]));
                    /*return \Response::json(array(
                        'success' => true,
                        'conv' => $real_conv,
                        'users'=>$conv_users,
                        'messages'=>$messages,
                    ));*/
                }
                
            }

        }
	}
       
}
