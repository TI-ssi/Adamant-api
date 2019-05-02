<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemBalance extends Model
{
    public function scopeNearestCurrency($query, $currency, $amount){
        return $query->where('currency', $currency)->where('balance', '>', $amount)->orderBy('balance');
    }

    public function wallet(){
        return $this->belongsTo('App\Models\Wallet');
    }

    protected $attributes = [
        'balance' => 0
    ];
    
    protected $guarded = [];
    
}
