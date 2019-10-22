<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $fillable = [
        'post_id', 'user_id'
    ];

    public function post()
    {
        return $this->hasOne('App\Posts', 'id', 'post_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
