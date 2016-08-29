<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ean','name', 'description', 'brand_id', 'picture', 'price', 'url','sell', 'created_at', 'updated_at', 'category_id'
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
        return $this->belongsToMany('App\User', 'users_equips_sports', 'user_id', 'product_id');
    }
    public function sport()
    {
        return $this->belongsToMany('App\Sport', 'users_equips_sports', 'sport_id', 'product_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function carac_vals()
    {
        return $this->hasMany('App\Carac_val');
    }


}
