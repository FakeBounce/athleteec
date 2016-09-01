<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'users_notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'userL_id', 'notification', 'libelle','action_id','action_name', 'accepter', 'afficher', 'created_at', 'updated_at'
    ];

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
            return $left.$tm.' minute'.($tm > 1 ? 's' : '').' '
            .$right;
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
                return $left.$th.' heure'.($th > 1 ? 's' : '').' '
                .$right;
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
