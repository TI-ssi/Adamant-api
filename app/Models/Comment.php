<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment',
        'user_id',
        'parent_id',
        'parent_type'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    protected $hidden = ['updated_at'];



}
