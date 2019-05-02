<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address'
    ];

    protected $hidden = ['updated_at', 'user_id'];

    protected $table = "coin_requests";

}
