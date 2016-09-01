<?php

namespace App\Http\Controllers\Front;

use App\HelperPublication;
use App\Http\Controllers\Controller;
use App\Publication;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if(empty($request->input('message_status')))
        {
            $request->merge([ 'message_status' => ".5656897299ad797df9f7ds9f87e9f879ze8f79ds7f98e7" ]);
        }
        $publication = HelperPublication::store($request);
        if($publication['message'] == ".5656897299ad797df9f7ds9f87e9f879ze8f79ds7f98e7")
        {
            $publication['message'] = "";
        }
        if(is_array($publication) && array_key_exists('errors',$publication)){
            return Redirect::back()->withErrors($publication['errors']);
        }

        Publication::create($publication);

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
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
    public function update(Request $request, Publication $publication)
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
    public function updateAjax(Request $request, Publication $publication)
    {
        if(\Request::ajax() && $publication != null){

            $publicationUpdate = HelperPublication::update($request,$publication);
            if(is_array($publicationUpdate) && array_key_exists('errors',$publicationUpdate)){
                return \Response::json(array(
                    'success' => false,
                    'errors' => $publicationUpdate['errors']
                ));
            }

            $publication = $publicationUpdate['publication'];
            $publication->save();
            $video = $publicationUpdate['video'];

            return \Response::json(array(
                'success' => true,
                'publication' => $publication,
                'video' => $video
            ));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAjax(Publication $publication)
    {
        if(\Request::ajax() && $publication != null) {

            if(HelperPublication::destroy($publication)){
                return \Response::json(array(
                    'success' => true
                ));
            }
        }
        return \Response::json(array(
            'success' => false
        ));
    }

    public function loadComment(Publication $publication,Request $request){

        if(\Request::ajax()) {
            $helper = new HelperPublication();
            $data = $helper->loadComments($publication,$request);

            return \Response::json($data);
        }
        return \Response::json(array(
            'success' => false
        ));
    }

    public function loadAll(Request $request){
        if(\Request::ajax()) {
            $helper = new HelperPublication();
            $data = $helper->loadAll($request);

            return \Response::json($data);
        }
        return \Response::json(array(
            'success' => false
        ));
    }



    public function signaleAjax(Publication $publication){
        if(\Request::ajax() && !is_null($publication)) {
            HelperPublication::signale($publication);

            return \Response::json(array(
                'success' => true
            ));
        }

        return \Response::json(array(
            'success' => false
        ));

    }




}
