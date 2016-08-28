<?php

namespace App\Http\Controllers\Front;

use App\Event;
use App\User;
use App\Http\Controllers\Controller;
use App\Publication;
use App\Sport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sports = Sport::all();
        $user = Auth::user();
        $arrayFriends = array();
        $arrayStars = array();
        $arrayFriends[] = $user->id;

        foreach($user->friends as $friend){
            $arrayFriends[] = $friend->id;
        }

        $posts = Publication::whereIn('user_id', $arrayFriends)
            ->where('status', '!=', 'Blocked')
            ->orderBy('updated_at', 'DESC')
            ->take(10)
            ->get();

        $arraySports = array();
        foreach($user->sports as $sport){
            $arraySports[] = $sport->id;
        }

        $events = Event::whereIn('sport_id', $arraySports )
            ->where('private', '=', '0')
            ->where('started_at', '>=', Carbon::today()->toDateString())
            ->orderBy('started_at', 'DESC')
            ->take(3)
            ->get();
        
        $stars = User::whereIn('id', $arrayFriends)
            ->where('star','1')
            ->get();
        
        if(!empty($stars))
        {
            foreach($stars as $star){
                $arrayStars[] = $star->id;
            }

            $star_posts = Publication::whereIn('user_id', $arrayStars)
            ->where('status', '!=', 'Blocked')
            ->orderBy('updated_at', 'DESC')
            ->take(1)
            ->get();

            return view('front.index', ["sports" => $sports, "publications" => $posts, "events" => $events, "star_pub" => $star_posts->first()]);
            
        }
        else
        {
            return view('front.index', ["sports" => $sports, "publications" => $posts, "events" => $events]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
