<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carac extends Model
{
    protected $table = 'caracs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','category_id','created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */    
    
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    public function value()
    {
        return $this->hasMany('App\Carac_val');
    }
}
