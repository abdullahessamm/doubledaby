<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
    //
    protected $fillable = [
        'customer_name', 'customer_email', 'customer_phone', 'is_member', 'member_id', 'total_price'
    ];
}
