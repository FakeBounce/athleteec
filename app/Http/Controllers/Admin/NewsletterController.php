<?php

namespace App\Http\Controllers\Admin;

use App\Newsletter;
use App\UsersNewsletters;
use App\Sport;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Redirect;

class NewsletterController extends Controller
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = Newsletter::all();
        return view('admin.newsletter.index', ['newsletters' => $newsletters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.newsletter.create');
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
        $rules = [
            'name' => 'required',
            'text' => 'required',
        ];
        $messages = [
            'name.required'    => 'L\'objet est obligatoire',
            'text.required'      => 'Le corps du message est obligatoire',
        ];

        $validator = Validator::make($data,$rules,$messages);

        if($validator->fails())
        {
            $request->flash();
            return Redirect::back()->withErrors($validator);
        }

        $newsletter = Newsletter::create(array(
            'objet' => $data['name'],
            'text' => $data['text']
        ));

        return Redirect::route('admin.newsletter.show', ['newsletter' => $newsletter]);
    }

    public function send(Request $request){
        /*

        */


        $data = $request->all();
        if(!empty($data['id']))
        {
            if(empty($data['sport']))
            {
                $users = UsersNewsletters::where('active', '=', 1)->get();
                $newsletter = Newsletter::where('id', '=', $data['id'])->get()->first();
                foreach($users as $user){
                    $this->mailer->send('emails.newsletter', ['text' => $newsletter->text ] ,function (Message $m) use ($user,$newsletter) {
                        $m->from('esgi.athleteec@gmail.com', 'Athleteec');
                        $m->to($user->email)->subject($newsletter->objet);
                    });
                }
                return \Response::json(array(
                    'success' => true,
                    'message' => 'Newsletter transmise avec succès'
                ));
           }
            else
            {   
                $users = UsersNewsletters::where('active', '=', 1)->get();
                $newsletter = Newsletter::where('id', '=', $data['id'])->get()->first();
                foreach($users as $user){
                $r_user = User::where('email', '=', $user->email)->get()->first();
                    $is_ok = 0;
                    foreach($data['sport'] as $sport)
                    {
                        if(!empty($r_user->sports))
                        {                  
                            foreach($r_user->sports as $user_sport)
                            {
                                if($user_sport->id == $sport)
                                {
                                    $is_ok = 1;
                                    break;
                                }
                            }
                            if($is_ok == 1)
                            {
                                break;
                            }
                        }
                    }
                    if($is_ok)
                    {           
                        $this->mailer->send('emails.newsletter', ['text' => $newsletter->text ] ,function (Message $m) use ($user,$newsletter) {
                        $m->from('esgi.athleteec@gmail.com', 'Athleteec');
                        $m->to($user->email)->subject($newsletter->objet);
                        });
                    }
                }
                return \Response::json(array(
                    'success' => true,
                    'message' => 'Newsletter transmise avec succès'
                ));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Newsletter $newsletter)
    {
        $sports = Sport::where('id','>',0)->get();
        return view('admin.newsletter.show', ['newsletter' => $newsletter,'sports' => $sports]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Newsletter $newsletter)
    {
        return view('admin.newsletter.edit', ['newsletter' => $newsletter]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'text' => 'required',
        ];
        $messages = [
            'name.required'    => 'L\'objet est obligatoire',
            'text.required'      => 'Le corps du message est obligatoire',
        ];

        $validator = Validator::make($data,$rules,$messages);

        if($validator->fails())
        {
            $request->flash();
            return Redirect::back()->withErrors($validator);
        }

        $newsletter->objet = $data['name'];
        $newsletter->text = $data['text'];
        $newsletter->save();

        return Redirect::route('admin.newsletter.show', ['newsletter' => $newsletter]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return Redirect::route('admin.newsletter.index');
    }
    
}
