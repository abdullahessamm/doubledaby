<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    protected $fillable = [
        'size', 'count', 'color_id',
    ];

    public function color_id()
    {
        return $this->hasOne('App\Colors', 'id', 'color_id');
    }
}
