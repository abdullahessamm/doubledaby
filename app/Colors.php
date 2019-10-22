<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colors extends Model
{
    //products colors
    protected $fillable = [
        'product_id', 'color', 'count'
    ];

    public function product()
    {
        return $this->hasOne('App\Products', 'id', 'product_id');
    }

}
