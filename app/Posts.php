<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $fillable = [
        'auther_id', 'discription', 'product_id'
    ];

    public function auther()
    {
        return $this->hasOne('App\User', 'id', 'auther_id');
    }

    public function product()
    {
        return $this->hasOne('App\Products', 'id', 'product_id');
    }

}
