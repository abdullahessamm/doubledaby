<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orderColor extends Model
{
    //
    protected $fillable = [
        'color', 'count', 'order_id', 'bill_id'
    ];

    public function order() {
        return $this->hasOne('App\order', 'id', 'order_id');
    }
}
