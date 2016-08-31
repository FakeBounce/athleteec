<?php

namespace App\Http\Controllers\Front;


use App\Event;
use App\HelperPublication;
use App\Notifications;
use App\Sport;
use App\User;
use App\UsersEvents;
use App\UsersDemandsEvents;
use App\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Validator;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $events = $user->events;
        $userSports = $user->sports;
        $arraySport = [];

        foreach($userSports as $us){
            $arraySport[] = $us->id;
        }

        $sports = DB::table('sports')
            ->whereNotIn('id', $arraySport)
            ->get();

        return view('front.event.index', ["events" => $events, "sports" => $sports, "userSports" => $userSports]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sports = Sport::all();
        return view('front.event.create', ['sports' => $sports]);
    }

    public function create_association($id)
    {
        $sports = Sport::all();
        return view('front.event.create', ['sports' => $sports,"association_id"=>$id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'picture' => 'required|mimes:jpeg,png,jpg',
            'lattitude' => 'required',
            'sport' => 'required',
            'date_start_act' => 'required',
            'date_end_act' => 'required'
        ];
        $messages = [
            'name.required'    => 'Le nom de l\'event est requis',
            'description.required'    => 'Description requise',
            'picture.required' => 'Image requise',
            'picture.mimes'      => 'Le format de l\'image n\'est pas pris en charge (jpeg,png,jpg)',
            'lattitude.required'      => 'Indiquer une adresse',
            'sport.required' => 'Choisir un sport',
            'date_start_act.required' => 'Choisir une date de début',
            'date_end_act.required' => 'Choisir une date de fin'
        ];
        $validator = Validator::make($data,$rules,$messages);

        if($validator->fails())
        {
            $request->flash();
            return Redirect::back()->withErrors($validator);
        }

        $imageName = null;
        if ($request->hasFile('picture')) {
            $guid = sha1(time());
            $imageName = $guid.'_assos.' . $request->file('picture')->getClientOriginalExtension();;

            $request->file('picture')->move(
                public_path() . '/images/evenements', $imageName
            );
            $imageName = '/images/evenements/'.$imageName;
        }
        if(!isset($data['association_id']))
        $data['association_id'] = 0;
        $event = Event::create(array(
            'name' => htmlspecialchars($data['name']),
            'picture' => $imageName,
            'address' => $data['route'],
            'city' => $data['locality'],
            'city_code' => $data['postal_code'],
            'lattitude' => $data['lattitude'],
            'longitude' => $data['longitude'],
            'number_street' => $data['street_number'],
            'region' => $data['region'],
            'country' => $data['country'],
            'user_id' => $user->id,
            'sport_id' => $data['sport'],
            'private' => $data['private'],
            'association_id' => $data['association_id'],
            'started_at' => $data['date_start_act'],
            'end_at' => $data['date_end_act']
        ));

        $event->description = $data['description'];
        $event->save();

        UsersEvents::create(array(
            'user_id' => $user->id,
            'event_id' => $event->id,
            'is_admin' => true
        ));

        return Redirect::route("event.show", ["event" => $event->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
       if(!$id->private || (Auth::user()->isAuthorisedEvent($id) || Auth::user()->isAdminEvent($id->id)))
       {
            $user = Auth::user();
            $sports = Sport::all();
            $ismember = DB::table('users_events')
                        ->where('event_id' ,'=', $id->id)
                        ->where('user_id' ,'=', $user->id)
                        ->get();

            return view('front.event.show', ["event" => $id, "sports" =>$sports, "isMember" => $ismember, "user" => $user]);
       }
       else
       {
           abort(403, 'Unauthorized action.');
       }
    }
    
    
    //Montrer les amis en JSON pour les ajouter a un event privé
    function showUser()
    {
            if(!empty(Input::get('event_id')) && !empty(Input::get('is_authorised')))
            {
                $event = Event::where('id',Input::get('event_id'))->first();
                
                if($event)
                {
                    
                    $event_users = $event->members;  
                    $event_auth_users = $event->authorisedMembers;  
                    $friends = Auth::user()->friends;
                    
                    foreach($friends as $friendKey => $friend)
                    {
                        $total_name = $friend->firstname.' '.$friend->lastname;
                        if(stripos($total_name,Input::get('is_authorised')) !== false)
                        {
                            foreach($event_users as $event_user)
                            {
                                if($friend->id == $event_user->user_id)
                                {
                                    unset($friends[$friendKey]);
                                }
                            }
                            foreach($event_auth_users as $event_auth_user)
                            {
                                if($friend->id == $event_auth_user->user_id && $event_auth_user->is_authorised == 1)
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
                        '$event_users' => $event_users,
                        ));
                }
                
            }
	}
    
    function authorise()
    {
            $friend = User::where('id',Input::get('friend_id'))->first();
            if(!empty(Input::get('is_authorised')) && !empty(Input::get('friend_id')))
            {
                $event = Event::where('id',Input::get('event_id'))->first();
                
                if($event)
                {
                    $potential_user = UsersDemandsEvents::where('event_id',"=",$event->id)->where('user_id',"=",$friend->id)->first();
                    if(!$potential_user)
                    {
                        $user_authorised = new UsersDemandsEvents();
                        $user_authorised->user_id = $friend->id;
                        $user_authorised->event_id = $event->id;
                        $user_authorised->is_authorised = 1;
                        $user_authorised->save();
                    }
                    else
                    {
                        $potential_user->is_authorised = 1;
                        $potential_user->save();
                    }
                    
                    return \Response::json(array(
                        'friend'=>$friend,
                        'event_id'=>$event->id,
                        'user'=>Auth::user(),
                    ));
                }
            }
	}
    
    function deleteUser()
    {
            $friend = User::where('id',Input::get('friend_id'))->first();
            if(!empty(Input::get('friend_id')))
            {
                $event = Event::where('id',Input::get('event_id'))->first();
                
                if($event)
                {
                    $user_authorised = UsersDemandsEvents::where('event_id',"=",$event->id)->where('user_id',"=",$friend->id)->first();
                    $user_authorised->is_authorised = 0;
                    $user_authorised->save();
                    
                    return \Response::json(array(
                        'friend'=>$friend,
                        'event_id'=>$event->id,
                        'user'=>Auth::user(),
                    ));
                }
            }
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
       if(!$event->private || (Auth::user()->isAuthorisedEvent($event) || Auth::user()->isAdminEvent($event->id)))
       {
            $user = Auth::user();
            if($user->isAdminEvent($event->id)){
                $sports = Sport::all();
                return view('front.event.edit', ['event' => $event, 'sports' => $sports]);
            }

            return Redirect::back();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->all();
        $user = Auth::user();
        //$data['date_start_act'] = date_create_from_format('Y-m-d H:i:s',$data['date_start_act']);
        //$data['date_end_act'] = strtotime($data['date_end_act']);
        if($user->isAdminEvent($event->id)){
            $rules = [
                'name' => 'required',
                'description' => 'required',
                'picture' => 'mimes:jpeg,png,jpg',
                'lattitude' => 'required',
                'sport' => 'required',
                'date_start_act' => 'required',
                'date_end_act' => 'required'
            ];
            $messages = [
                'name.required'    => 'Le nom de l\'event est requis',
                'description.required'    => 'Description requise',
                'picture.mimes'      => 'Le format de l\'image n\'est pas pris en charge (jpeg,png,jpg)',
                'lattitude.required'      => 'Indiquer une adresse',
                'sport.required' => 'Choisir un sport',
                'date_start_act.required' => 'Choisir une date de début',
                'date_end_act.required' => 'Choisir une date de fin'
            ];
            $validator = Validator::make($data,$rules,$messages);

            if($validator->fails())
            {
                $request->flash();
                return Redirect::back()->withErrors($validator);
            }

            if ($request->hasFile('picture')) {
                $guid = sha1(time());
                $imageName = $guid.'_assos.' . $request->file('picture')->getClientOriginalExtension();;

                $request->file('picture')->move(
                    public_path() . '/images/evenements', $imageName
                );
                $event->picture = '/images/evenements/'.$imageName;
            }

            $event->name = htmlspecialchars($data['name']);
            $event->address = $data['route'];
            $event->city = $data['locality'];
            $event->city_code = $data['postal_code'];
            $event->lattitude = $data['lattitude'];
            $event->longitude = $data['longitude'];
            $event->number_street = $data['street_number'];
            $event->region = $data['region'];
            $event->country = $data['country'];
            $event->sport_id = $data['sport'];
            $event->private = $data['private'];
            $event->started_at = $data['date_start_act'];
            $event->end_at = $data['date_end_act'];
            $event->description = $data['description'];
            
            $event->save();

            return Redirect::route('event.show', ['event' => $event->id])->with('message', 'Modification effectué avec succès');
        }

        return Redirect::back()->withErrors("Vous n'êtes pas autorisé");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Event $event)
    {
        $user = Auth::user();
        if($user->isAdminEvent($event->id)) {
            foreach($event->publications as $post){
                foreach($post->comments as $comment){
                    $comment->delete();
                }
                $post->delete();
            }

            foreach($event->members as $relation){
                $relation->delete();
            }

            $event->delete();

            return Redirect::to("/");
        }

        return Redirect::back();
    }

    /**
     * Join the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function join(Event $event)
    {
       $userC = Auth::user();
       $members = $event->members;
       foreach($members as $member){
           $user = $member->user;
           if(!is_null($user)){
               Notifications::firstOrCreate([
                   'user_id' => $user->id,
                   'userL_id' => $userC->id, //OSEF du nom de la colonne, on récupère les bonnes info grace à la colone notification.
                   'libelle' => $userC->firstname." ".$userC->lastname,
                   'action_id' => $event->id,
                   'action_name' => $event->name,
                   'notification' => 'events',
                   'accepter' => 1,
                   'afficher' => true]);
           }
           
           UsersDemandsEvents::firstOrCreate([
                'user_id' => $userC->id,
                'event_id' =>  $event->id,
                'is_authorised' => 1
                ]);

           
           UsersEvents::create(array(
              'user_id' => $userC->id,
               'event_id' => $event->id,
               'is_admin' => false
           ));
       }

       return Redirect::back();
    }

    public function quit(Event $event){
        $userC = Auth::user();
        $userAssocation = UsersEvents::where('user_id', '=', $userC->id)
            ->where('event_id', '=', $event->id)
            ->get();

        if($userAssocation != null && $userAssocation->count() ==1){
            $userAssocation[0]->delete();
        }

        return Redirect::route('event.index');
    }

    //Publication

    public function storepost(Request $request, Event $event){
        $publication = HelperPublication::store($request);
        if(is_array($publication) && array_key_exists('errors',$publication)){
            return Redirect::back()->withErrors($publication['errors']);
        }
        $publication['event_id'] = $event->id;
        Publication::create($publication);

        return Redirect::back();

    }

    public function storeact(Request $request, Event $event){
        $publicationArray = HelperPublication::store($request);
        if(is_array($publicationArray) && array_key_exists('errors',$publicationArray)){
            return Redirect::back()->withErrors($publicationArray['errors']);
        }
        $activityArray = HelperActivity::store($request,$publicationArray);

        $activity = Activity::create($activityArray);

        $publicationArray['activity_id'] = $activity->id;
        $publication['event_id'] = $event->id;
        Publication::create($publicationArray);

        return Redirect::back();

    }

    public function search(Request $request){
        $data = $request->all();
        if(array_key_exists('sports', $data)){
            $events = Event::whereIn('sport_id', $data['sports'])->get();
            
            foreach($events as $key => $event)
            {
                
                if($event->private && (!(Auth::user()->isAuthorisedEvent($event)) && !(Auth::user()->isAdminEvent($event->id))))
                {
                    unset($events[$key]);
                }
            }
            return \Response::json(array(
                'success' => true,
                'events' => $events
            ));
        }

        return \Response::json(array(
            'success' => false
        ));
    }

    public function promouvoir(UsersEvents $usersEvents){
        $usersEvents->is_admin = true;
        $usersEvents->save();

        return \Response::json(array(
            'success' => true
        ));
    }

    public function destituer(UsersEvents $usersEvents){
        $usersEvents->is_admin = false;
        $usersEvents->save();

        return \Response::json(array(
            'success' => true
        ));
    }
    


}
