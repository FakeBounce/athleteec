<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;
    use EntrustUserTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'firstname', 'lastname','password', 'email', 'birthday', 'sexe', 'status','token_email','score', 'picture', 'newsletter', 'created_at', 'updated_at', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function sports()
    {
        return $this->belongsToMany('App\Sport', 'users_sports', 'user_id', 'sport_id');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }
    
    public function conversations_interlocutor()
    {
         return $this->belongsToMany('App\User','conversation_users', 'user_id');
    }

    public function publications()
    {
        return $this->hasMany('App\Publication');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'users_groups', 'user_id', 'group_id');
    }

    public function conversations()
    {
        return $this->hasMany('App\Conversation_user');
    }

    public function equipments()
    {
        return $this->hasMany('App\Equipment', 'users_equips_sports', 'user_id', 'product_id');
    }
    
    public function friends(){
        return $this->belongsToMany('App\User','users_links','user_id','userL_id');
    }

    // les demandes d'amis reçues
    public function demandsto(){
        return $this->belongsToMany('App\User','users_demands','user_id','userL_id')
                    ->where('demands', false);
    }
    // les demandes d'amis envoyées
    public function demandsfrom(){
        return $this->belongsToMany('App\User','users_demands', 'userL_id', 'user_id')
                    ->where('demands', false);
    }

    public function getnotifications(){
        return $this->hasMany('App\Notifications')
            ->where('afficher', true);
    }

    public function getfriendsnotificationstrue(){
        return $this->hasMany('App\Notifications')
            ->where('afficher', true)
            ->where('notification', 'users_links');
    }

    public function getfriendsnotifications(){
        return $this->hasMany('App\Notifications')
            ->where('notification', 'users_links')
            ->limit(8);
    }

    public function geteventsnotificationstrue(){
        return $this->hasMany('App\Notifications')
            ->where('afficher', true)
            ->where('notification', 'events');
    }

    public function geteventsnotifications(){
        return $this->hasMany('App\Notifications')
            ->where('notification', 'events')
            ->limit(8);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

}
