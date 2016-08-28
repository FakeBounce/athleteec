<?php

namespace App\Http\Controllers\Front;


use App\Association;
use App\HelperPublication;
use App\Notifications;
use App\Sport;
use App\User;
use App\UsersAssociations;
use App\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Validator;
use App\Http\Controllers\Controller;

class AssociationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function index()
    {
        $user = Auth::user();
        $associations = $user->associations;
        $userSports = $user->sports;
        $arraySport = [];

        foreach($userSports as $us){
            $arraySport[] = $us->id;
        }

        $sports = DB::table('sports')
            ->whereNotIn('id', $arraySport)
            ->get();

        return view('front.association.index', ["associations" => $associations, "sports" => $sports, "userSports" => $userSports]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sports = Sport::all();
        return view('front.association.create', ['sports' => $sports]);
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
            'sport' => 'required'
        ];
        $messages = [
            'name.required'    => 'Le nom de l\'association est requis',
            'description.required'    => 'Description requise',
            'picture.required' => 'Image requise',
            'picture.mimes'      => 'Le format de l\'image n\'est pas pris en charge (jpeg,png,jpg)',
            'lattitude.required'      => 'Indiquer une adresse',
            'sport.required' => 'Choisir un sport'
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
                public_path() . '/images/associations', $imageName
            );
            $imageName = '/images/associations/'.$imageName;
        }

        $association = Association::create(array(
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
            'facebook' => htmlspecialchars($data['facebook']),
            'twitter' => htmlspecialchars($data['twitter']),
            'google' => htmlspecialchars($data['google'])
        ));

        $association->description = $data['description'];
        $association->save();

        UsersAssociations::create(array(
            'user_id' => $user->id,
            'association_id' => $association->id,
            'is_admin' => true
        ));

        return Redirect::route("association.show", ["association" => $association->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $sports = Sport::all();
        $ismember = DB::table('users_associations')
                    ->where('association_id' ,'=', $id->id)
                    ->where('user_id' ,'=', $user->id)
                    ->get();

        return view('front.association.show', ["association" => $id, "sports" =>$sports, "isMember" => $ismember, "user" => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Association $association)
    {
        $user = Auth::user();
        if($user->isAdminAssociation($association->id)){
            $sports = Sport::all();
            return view('front.association.edit', ['association' => $association, 'sports' => $sports]);
        }

        return Redirect::back();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function msg(Request $request, Association $association)
    {
        $data = $request->all();
        $user = Auth::user();
        if($user->isAdminAssociation($association->id)){
            
            foreach($association->members as $member)
            {
                $actual_member = $member->user;
                $this->mailer->send('emails.newsletter', ['text' => $data['text'] ] ,function (Message $m) use ($user,$actual_member,$association) {
                        $m->from($user->email, 'Athleteec - Association : '.$association->name);
                        $m->to($actual_member->email)->subject('Athleteec - Association : '.$association->name.', Message groupé');
                });
            }
            
            return \Response::json(array(
                'success' => true
            ));
        }        
        
        return \Response::json(array(
            'success' => false
        ));
    }
    public function update(Request $request, Association $association)
    {
        $data = $request->all();
        $user = Auth::user();
        if($user->isAdminAssociation($association->id)){
            $rules = [
                'name' => 'required',
                'description' => 'required',
                'picture' => 'mimes:jpeg,png,jpg',
                'lattitude' => 'required',
                'sport' => 'required'
            ];
            $messages = [
                'name.required'    => 'Le nom de l\'association est requis',
                'description.required'    => 'Description requise',
                'picture.mimes'      => 'Le format de l\'image n\'est pas pris en charge (jpeg,png,jpg)',
                'lattitude.required'      => 'Indiquer une adresse',
                'sport.required' => 'Choisir un sport'
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
                    public_path() . '/images/associations', $imageName
                );
                $association->picture = '/images/associations/'.$imageName;
            }

            $association->name = htmlspecialchars($data['name']);
            $association->address = $data['route'];
            $association->city = $data['locality'];
            $association->city_code = $data['postal_code'];
            $association->lattitude = $data['lattitude'];
            $association->longitude = $data['longitude'];
            $association->number_street = $data['street_number'];
            $association->region = $data['region'];
            $association->country = $data['country'];
            $association->sport_id = $data['sport'];
            $association->facebook = htmlspecialchars($data['facebook']);
            $association->twitter = htmlspecialchars($data['twitter']);
            $association->google = htmlspecialchars($data['google']);
            $association->description = $data['description'];

            $association->save();

            return Redirect::route('association.show', ['association' => $association->id])->with('message', 'Modification effectué avec succès');
        }

        return Redirect::back()->withErrors("Vous n'êtes pas autorisé");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Association $association)
    {
        $user = Auth::user();
        if($user->isAdminAssociation($association->id)) {
            foreach($association->publications as $post){
                foreach($post->comments as $comment){
                    $comment->delete();
                }
                $post->delete();
            }

            foreach($association->members as $relation){
                $relation->delete();
            }

            $association->delete();

            return Redirect::to("/");
        }

        return Redirect::back();
    }

    /**
     * Join the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function join(Association $association)
    {
       $userC = Auth::user();
       $members = $association->members;
       foreach($members as $member){
           $user = $member->user;
           if(!is_null($user)){
               Notifications::firstOrCreate([
                   'user_id' => $user->id,
                   'userL_id' => $association->id,
                   'libelle' => $userC->firstname." ".$userC->lastname,
                   'action_id' => $association->id,
                   'action_name' => $association->name,
                   'notification' => 'associations',
                   'afficher' => true]);
           }

       }

       UsersAssociations::create(array(
          'user_id' => $userC->id,
           'association_id' => $association->id,
           'is_admin' => false
       ));


       return Redirect::back();
    }

    public function quit(Association $association){
        $userC = Auth::user();
        $userAssocation = UsersAssociations::where('user_id', '=', $userC->id)
            ->where('association_id', '=', $association->id)
            ->get();

        if($userAssocation != null && $userAssocation->count() ==1){
            $userAssocation[0]->delete();
        }

        return Redirect::route('association.index');
    }

    //Publication

    public function storepost(Request $request, Association $association){
        $publication = HelperPublication::store($request);
        if(is_array($publication) && array_key_exists('errors',$publication)){
            return Redirect::back()->withErrors($publication['errors']);
        }

        $publication['association_id'] = $association->id;
        Publication::create($publication);

        return Redirect::back();

    }

    public function storeact(Request $request, Association $association){
        $publicationArray = HelperPublication::store($request);
        if(is_array($publicationArray) && array_key_exists('errors',$publicationArray)){
            return Redirect::back()->withErrors($publicationArray['errors']);
        }
        $activityArray = HelperActivity::store($request,$publicationArray);

        $activity = Activity::create($activityArray);

        $publicationArray['activity_id'] = $activity->id;
        $publication['association_id'] = $association->id;
        Publication::create($publicationArray);

        return Redirect::back();

    }

    public function search(Request $request){
        $data = $request->all();
        if(array_key_exists('sports', $data)){
            $associations = Association::whereIn('sport_id', $data['sports'])->get();

            return \Response::json(array(
                'success' => true,
                'associations' => $associations
            ));
        }

        return \Response::json(array(
            'success' => false
        ));
    }

    public function promouvoir(UsersAssociations $usersAssociations){
        $usersAssociations->is_admin = true;
        $usersAssociations->save();

        return \Response::json(array(
            'success' => true
        ));
    }

    public function destituer(UsersAssociations $usersAssociations){
        $usersAssociations->is_admin = false;
        $usersAssociations->save();

        return \Response::json(array(
            'success' => true
        ));
    }
}
