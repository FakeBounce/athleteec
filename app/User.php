<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Support\Facades\DB;

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
       'id', 'firstname', 'lastname','password', 'email', 'birthday', 'sexe', 'status','token_email','job','firm','school','address','score', 'picture', 'newsletter', 'created_at', 'updated_at', 'activated','star'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getPictureAttribute(){
        if(is_null($this->attributes['picture'])) {
            return '/asset/img/avatars/avatar.png';
        }
        return $this->attributes['picture'];
    }

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
        return $this->hasMany('App\Publication')->where("status","!=","Blocked")->orderby('created_at','DESC');
    }

    public function publicationsAll()
    {
        return $this->hasMany('App\Publication');
    }

    public function commentsAll()
    {
        return $this->hasMany('App\Comment');
    }

    public function associations(){
        return $this->hasMany('App\UsersAssociations');
    }

    public function videos(){
        $video = DB::table('publications')
            ->join('videos', 'publications.video_id', '=', 'videos.id')
            ->where('publications.user_id', '=', $this->id)
            ->select('videos.*')
            ->get();

        return $video;
    }

    public function isMemberAssociation($id){
        foreach($this->associations as $association){
            if($association->association_id == $id)
                return true;
        }
        return false;
    }

    public function isAdminAssociation($id){
        foreach($this->associations as $association){
            if($association->association_id == $id && $association->is_admin)
                return true;
        }
        return false;
    }

    public function demandsPersonnalite(){
        return $this->hasOne('App\UsersDemandsStars');
    }

    public function events(){
        return $this->hasMany('App\UsersEvents');
    }

    public function isMemberEvent($id){
        foreach($this->events as $event){
            if($event->event_id == $id)
                return true;
        }
        return false;
    }
    
    public function isAuthorisedEvent($event){
        foreach($event->authorisedMembers as $event_auth_member)
        {
            if($event_auth_member->user_id == $this->id && $event_auth_member->is_authorised == 1)
            return true;
        }
        return false;
    }

    public function isAdminEvent($id){
        foreach($this->events as $event){
            if($event->event_id == $id && $event->is_admin)
                return true;
        }
        return false;
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'users_groups', 'user_id', 'group_id');
    }

    public function conversations()
    {
        return $this->hasMany('App\Conversation_user');
    }
    
    public function conversations_reverse()
    {
        return $this->conversations()->orderBy('updated_at', 'desc');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'users_equips_sports', 'user_id', 'product_id');
    }
    
    public function friends(){
        return $this->belongsToMany('App\User','users_links','user_id','userL_id');
    }

    public function isfriend($userid, $friendid){
        $isfriend = $this->demandsto()
            ->where('user_id', $userid)
            ->where('userL_id', $friendid)
            ->get();
        if(count($isfriend)>0){
            return 'demandsto';
        }

        $isfriend = $this->demandsfrom()
            ->where('user_id', $friendid)
            ->where('userL_id', $userid)
            ->get();
        if(count($isfriend)>0){
            return 'demandsfrom';
        }

        $isfriend = $this->friends()
            ->where('user_id', $userid)
            ->where('userL_id', $friendid)
            ->get();
        if(count($isfriend)>0){
            return 'estami';
        }
        return false;
    }

    public function pictures()
    {
        return $this->hasMany('App\Picture');
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

    public function notifications(){
        return $this->hasMany('App\Notifications')
            ->where('afficher', true)
            ->whereIn('notification', ['events', 'associations', 'groups'])
            ->orderBy('updated_at', 'DESC');
    }

    
        public function getallnotifications(){
   return $this->hasMany('App\Notifications')
       ->whereIn('notification', ['events', 'associations', 'groups', 'users_links', 'produitsajout', 'produitsajoutstar'])
       ->orderBy('created_at', 'DESC');
    }

    public function getnotifications(){
       return $this->hasMany('App\Notifications')
           ->where('afficher', true)
           ->where('notification', '!=' ,'users_links')
           ->orderBy('id', 'DESC');
    }

    public function getnotificationstrue(){
       return $this->hasMany('App\Notifications')
           ->where('afficher', true)
           ->where('notification', '!=' ,'users_links')
           ->orderBy('id', 'DESC');
    }
    public function getfriendsnotificationstrue(){
       return $this->hasMany('App\Notifications')
           ->where('afficher', true)
           ->where('notification', 'users_links')
           ->orderBy('updated_at', 'DESC')
           ->get();
    }

    public function getfriendsnotifications(){
        return $this->hasMany('App\Notifications')
            ->where('notification', 'users_links')
            ->orderBy('updated_at', 'DESC')
            ->limit(8);
    }

    public function geteventsnotificationstrue(){
        return $this->hasMany('App\Notifications')
            ->where('afficher', true)
            ->where('notification', 'events')
            ->orderBy('updated_at', 'DESC');
    }

    public function geteventsnotifications(){
        return $this->hasMany('App\Notifications')
            ->where('notification', 'events')
            ->orderBy('updated_at', 'DESC')
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

    public function timeAgo($timestamp, $ref = 0){
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        $time = $timestamp->timestamp;
        if ($ref < 1) $ref = time();

        $ts = $ref - $time;
        $past = $ts > 0;
        $ts = abs($ts);

        if ($past) {
            $left = 'Il y a ';
            $right = '';
        }
        else {
            $left = 'Il y a ';
            $right = '';
        }

        if ($ts === 0) return 'A l\'instant';

        if ($ts === 1) return $left.'1 seconde'.$right;

        // Less than 1 minute
        if ($ts < 60) return $left.$ts.' secondes'.$right;

        $tm = floor($ts / 60);
        $ts = $ts - $tm * 60;

        // Less than 3 hours
        if ($tm < 3 && $ts > 0) {
            return $left.$tm.' minute'.($tm > 1 ? 's' : '').' et '
            .$ts.' seconde'.($ts > 1 ? 's' : '').$right;
        }

        // Less than 1 hour
        if ($tm < 60) {
            if ($ts > 0) {
                $left = 'Il y a ';
            }
            return $left.$tm.' minute'.($tm > 1 ? 's' : '').$right;
        }

        $th = floor($tm / 60);
        $tm = $tm - $th * 60;

        // Less than 3 hours
        if ($th < 3) {
            if ($tm > 0) {
                return $left.$th.' heure'.($th > 1 ? 's' : '').' et '
                .$tm.' minute'.($tm > 1 ? 's' : '').$right;
            }
            else {
                return $left.$th.' heure'.($th > 1 ? 's' : '').$right;
            }
        }

        $refday = strtotime(date('d-m-Y', $ref));
        $refyday = strtotime(date('d-m-Y', $ref - 86400));

        // Same day, or yesterday
        if ($time >= $refyday) {
            if ($time < $refday) {
                $left = 'Hier ';
                $right = '';
            }
            else {
                $left = 'Aujourd\'hui ';
                $right = '';
            }
            return $left.' à '.date('H:i', $time).' '.$right;
        }

        $td = floor($th / 24);
        $th = $th - $td * 24;

        // Less than 3 days
        if ($td < 3) {
            $left = '';
            $right = '';
            return $left.ucfirst(strftime("%A", $time)).' à '
            .date('H:i', $time).$right;
        }

        // Less than 5 days
        if ($td < 5) {
            return $left.$td.' jours'.$right;
        }

        $refday = strtotime(date('Y-m-01', $ref));

        $right = '';

        // Same month
        if ($time >= $refyday) {
            $left = 'Le ';
            return $left.strftime("%A %d", $time).$right;
        }

        return 'Le '.strftime("%A %d %B", $time).$right;
    }

}
