<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderColorSize extends Model
{
    //
    protected $fillable = [
        'size', 'count', 'color_id', 'order_id', 'bill_id'
    ];

    public function color()
    {
        return $this->hasOne('App\orderColor', 'id', 'color_id');
    }
}
