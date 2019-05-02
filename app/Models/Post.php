<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'content'
    ];

    

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id')->orderBy('created_at');
    }
    
    protected $hidden = ['updated_at', 'user_id'];



}
