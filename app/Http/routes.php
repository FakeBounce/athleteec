<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

    // Place all your web routes here...
    Route::model('user', 'App\User');
    Route::model('publication', 'App\Publication');
    Route::model('activity', 'App\Activity');
    Route::model('comment', 'App\Comment');
    Route::model('association', 'App\Association');
    Route::model('userassociation', 'App\UsersAssociations');
    Route::model('event', 'App\Event');
    Route::model('userevent', 'App\UsersEvents');
    Route::model('notification', 'App\Notifications');



    Route::auth();
    Route::get('user/activation/{token}', 'Auth\AuthController@activateUser')->name('user.activate');

//Social Login
    Route::get('/login/redirect/{provider}', 'SocialAuthController@redirect');
    Route::get('/login/callback/{provider}', 'SocialAuthController@callback');

    Route::group(['middleware' => 'auth'], function () {

        Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
            Route::get('/', 'Admin\AdminController@index');
            Route::get('/datauser', 'Admin\AdminController@datauser');
            Route::resource('user', 'Admin\UserController');
            Route::resource('publication', 'Admin\PublicationController');
            Route::get('/comment/{comment}/destroy',  ['as' => 'comment.destroy', 'uses' => 'Admin\CommentController@destroy']);
        });

    Route::group(['middleware' => ['role:user|admin']], function () {
        Route::get('/', 'Front\IndexController@index');
        Route::get('/friends', 'Front\FriendsController@getAllFriends');

        //Chat
        Route::resource('conversation', 'ConversationController',['only' => ['index']]);
        Route::post('sendmessage', ['as' => 'sendmessage', 'uses' => 'ConversationController@sendMessage']);
        Route::post('create_conversation', ['as' => 'create_conversation', 'uses' => 'ConversationController@create']);
        Route::post('show_conversation', ['as' => 'show_conversation', 'uses' => 'ConversationController@show']);
        Route::post('change_conversation_name', ['as' => 'change_conversation_name', 'uses' => 'ConversationController@changeName']);
        Route::post('chat_add_user', ['as' => 'chat_add_user', 'uses' => 'ConversationController@addUser']);
        Route::post('chat_show_user', ['as' => 'chat_show_user', 'uses' => 'ConversationController@showUser']);
        Route::resource('publication', 'Front\PublicationController');
        Route::post('/publication/{publication}/loadComment', 'Front\PublicationController@loadComment');
        Route::post('/publication/{publication}/updateAjax', 'Front\PublicationController@updateAjax');
        Route::post('/publication/{publication}/destroyAjax', 'Front\PublicationController@destroyAjax');
        Route::post('/publication/{publication}/signaleAjax', 'Front\PublicationController@signaleAjax');
        Route::post('/publication/loadAll', 'Front\PublicationController@loadAll');

        //Profil
        Route::resource('user', 'UserController',['except' => ['update']]);
        Route::post('/user/{user}/update', ['as' => 'user.update', 'uses' => 'UserController@update']);

        //Activité
        Route::resource('activity', 'Front\ActivityController');
        Route::post('/activity/{activity}/updateAjax', 'Front\ActivityController@updateAjax');
        Route::post('/activity/{activity}/destroyAjax', 'Front\ActivityController@destroyAjax');
        Route::post('/activity/{activity}/signaleAjax', 'Front\ActivityController@signaleAjax');


        //Commentaire
        Route::resource('comment', 'Front\CommentController');

        //Association
        Route::resource('association', 'Front\AssociationController',['except' => ['update']]);
        Route::post('/association/{association}/update', ['as' => 'association.update', 'uses' => 'Front\AssociationController@update']);
        Route::get('/association/{association}/delete', ['as' => 'association.delete', 'uses' => 'Front\AssociationController@delete']);
        Route::post('/association/{association}/post', ['as' => 'association.post.store', 'uses' => 'Front\AssociationController@storepost']);
        Route::post('/association/{association}/act', ['as' => 'association.act.store', 'uses' => 'Front\AssociationController@storeact']);
        Route::get('/association/{association}/join', ['as' => 'association.join', 'uses' => 'Front\AssociationController@join']);
        Route::get('/association/{association}/quit', ['as' => 'association.quit', 'uses' => 'Front\AssociationController@quit']);
        Route::post('/association/{userassociation}/promouvoir', ['as' => 'association.promot', 'uses' => 'Front\AssociationController@promouvoir']);
        Route::post('/association/{userassociation}/destituer', ['as' => 'association.dest', 'uses' => 'Front\AssociationController@destituer']);
        Route::post('/association/search', ['as' => 'association.search', 'uses' => 'Front\AssociationController@search']);

        //Evenement
        Route::resource('event', 'Front\EventController',['except' => ['update']]);
        Route::post('/event/{event}/update', ['as' => 'event.update', 'uses' => 'Front\EventController@update']);
        Route::get('/event/{event}/delete', ['as' => 'event.delete', 'uses' => 'Front\EventController@delete']);
        Route::post('/event/{event}/post', ['as' => 'event.post.store', 'uses' => 'Front\EventController@storepost']);
        Route::post('/event/{event}/act', ['as' => 'event.act.store', 'uses' => 'Front\EventController@storeact']);
        Route::get('/event/{event}/join', ['as' => 'event.join', 'uses' => 'Front\EventController@join']);
        Route::get('/event/{event}/quit', ['as' => 'event.quit', 'uses' => 'Front\EventController@quit']);
        Route::post('/event/{userevent}/promouvoir', ['as' => 'event.promot', 'uses' => 'Front\EventController@promouvoir']);
        Route::post('/event/{userevent}/destituer', ['as' => 'event.dest', 'uses' => 'Front\EventController@destituer']);
        Route::post('/event/search', ['as' => 'event.search', 'uses' => 'Front\EventController@search']);
        Route::post('/event/showuser', ['as' => 'event.showuser', 'uses' => 'Front\EventController@showUser']);
        Route::post('/event/authorise', ['as' => 'event.authorise', 'uses' => 'Front\EventController@authorise']);
        Route::post('/event/deleteuser', ['as' => 'event.deleteuser', 'uses' => 'Front\EventController@deleteUser']);
        
        // Groupes
        Route::resource('groups', 'GroupController');
        Route::get('groups/{group}', ['as' => 'group.index', 'uses' => 'GroupController@index']);
        Route::get('groups/create', 'GroupController@createForm');
        Route::get('groups/create/{group}',['as' => 'group.create', 'uses' => 'GroupController@create']);


        //Amis
        Route::get('friends', ['as' => 'front.friends.show', 'uses' => 'Front\FriendsController@index']);
        Route::get('friends/destroy/{user}', ['as' => 'front.friends.destroy', 'uses' => 'Front\FriendsController@destroy']);
        Route::get('friends/add/{user}', ['as' => 'front.friends.add', 'uses' => 'Front\FriendsController@add']);
        Route::get('friends/cancel/{user}', ['as' => 'front.friends.cancel', 'uses' => 'Front\FriendsController@cancel']);
        Route::get('friends/accept/{user}', ['as' => 'front.friends.accept', 'uses' => 'Front\FriendsController@accept']);


        //Recherche
        Route::get('search', ['as' => 'front.search.show', 'uses' => 'Front\SearchController@search']);
        Route::get('notifications', ['as' => 'front.notifications.show', 'uses' => 'Front\NotificationsController@index']);
        Route::post('notifications/{notification}/see', ['as' => 'notification.see', 'uses' => 'Front\NotificationsController@see']);
        Route::get('message', ['as' => 'front.message.show', 'uses' => 'Front\MessagesController@index']);


        });

        Route::get('/uploads/{image}', function($image){

            //do so other checks here if you wish

            if(!File::exists( $image=storage_path("uploads/{$image}") )) abort(404);

            return Image::make($image)->response('jpg'); //will ensure a jpg is always returned
        });
    });
