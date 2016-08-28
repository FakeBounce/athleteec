<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'publications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','message', 'picture', 'user_id', 'activity_id', 'group_id', 'event_id', 'association_id', 'created_at', 'updated_at', 'video_id', 'score', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function video()
    {
        return $this->belongsTo('App\Video');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function association()
    {
        return $this->belongsTo('App\Association');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function comments(){
        return $this->hasMany('App\Comment')->where("status","!=","Blocked");
    }

    public function commentsALl(){
        return $this->hasMany('App\Comment');
    }

    public function commentspost()
    {
        return $this->comments()->orderBy('created_at', 'asc')->take(3);
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
