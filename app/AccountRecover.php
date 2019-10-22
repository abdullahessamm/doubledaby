<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountRecover extends Model
{
    //
    protected $fillable = [
        'user_id', 'code', 'recover_token'
    ];

    public function user_id()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
