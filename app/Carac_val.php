<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carac_val extends Model
{
    protected $table = 'carac_vals';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','value','carac_id','product_id','created_at', 'updated_at'
    ];
    
    
    public function carac()
    {
        return $this->belongsTo('App\Carac');
    }
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
