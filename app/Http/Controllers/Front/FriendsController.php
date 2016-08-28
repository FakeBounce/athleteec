<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\UsersDemands;
use App\UsersLinks;
use Illuminate\Support\Facades\Input;
use DB;
use App\User;
use App\Notifications;

class FriendsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(){
   $user = Auth::user();

   Notifications::where('user_id', $user->id)
       ->where('notification', 'users_links')
       ->update(['afficher' => false]);

   return view('front.friends', ['user' => $user]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($friend){
        $id = Auth::user()->id;
        $idfriend = $friend->id;

        UsersLinks::where('user_id', $id)
            ->where('userL_id', $idfriend)
            ->delete();

        UsersLinks::where('user_id', $idfriend)
            ->where('userL_id', $id)
            ->delete();

        UsersDemands::where('user_id', $id)
            ->where('userL_id', $idfriend)
            ->delete();

        UsersDemands::where('user_id', $idfriend)
            ->where('userL_id', $id)
            ->delete();

        Notifications::where('user_id', $idfriend)
            ->where('userL_id', $id)
            ->delete();

        Notifications::where('user_id', $id)
            ->where('userL_id', $idfriend)
            ->delete();

        return redirect('/friends');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel($friend){
        $id = Auth::user()->id;
        $idfriend = $friend->id;

        UsersDemands::where('user_id', $id)
            ->where('userL_id', $idfriend)
            ->delete();

        UsersDemands::where('user_id', $idfriend)
            ->where('userL_id', $id)
            ->delete();

        Notifications::where('user_id', $idfriend)
            ->where('userL_id', $id)
            ->delete();

        Notifications::where('user_id', $id)
            ->where('userL_id', $idfriend)
            ->delete();

        return redirect('/friends');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($friend){
        $iduser = Auth::user()->id;
        $idfriend = $friend->id;

        if($iduser !== $idfriend){
             UsersDemands::firstOrCreate([
                    'user_id' => $idfriend,
                    'userL_id' => $iduser,
                    'demands' => false]);

             Notifications::firstOrCreate([
                'user_id' => $idfriend,
                'userL_id' => $iduser,
                'libelle' => Auth::user()->firstname.' '.Auth::user()->lastname,
                'notification' => 'users_links',
                'afficher' => true]);
        }
        return redirect('/friends');
    }

    public function accept($friend){
        $iduser = Auth::user()->id;
        $idfriend = $friend->id;

        if($iduser !== $idfriend){
            UsersDemands::where('user_id', $iduser)
                ->where('userL_id', $idfriend)
                ->update(['demands' => true]);

            Notifications::where('user_id', $iduser)
                ->where('userL_id', $idfriend)
                ->where('notification', 'users_links')
                ->update(['afficher' => false]);

            UsersLinks::firstOrCreate([
                'user_id' => $idfriend,
                'userL_id' => $iduser]);

            UsersLinks::firstOrCreate([
                'user_id' => $iduser,
                'userL_id' => $idfriend]);

        }
        return redirect('/friends');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
