<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessages extends Model
{
    protected $fillable = [
        'full_name', 'email', 'title', 'subject'
    ];
}
