<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    //
    protected $fillable = [
        'name', 'count', 'piece_price', 'total_price', 'bill_id'
    ];
}
