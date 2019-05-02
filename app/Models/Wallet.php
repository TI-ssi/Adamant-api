<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    public function scopeEthereumAddress($query){
        return $query->select('user_id', 'address')->where('blockchain', 'ethereum')->where('id', '>', '0');
    }

    public function system_balances(){
        return $this->hasMany('App\Models\SystemBalance');
    }
    
    public function balances(){
        return $this->hasMany('App\Models\Balance');
    }

    public function scopeCurrencyBalance($query, $currency){
        return $this->balances()->where('currency', $currency);
    }

    
    public function scopeMerchant($query, $id){
        return $query->where('merchant_id', $id);
    }

    public function scopePersonnal($query){
        return $query->where('merchant_id', '0');
    }

    public function scopeBlockchain($query, $blockchain){
        return $query->where('blockchain', $blockchain);
    }


    protected $fillable = [
        'user_id',
        'blockchain',
        'address',
        'merchant_id',
        'hash'
    ];

    protected $attributes = [
           'blockchain' => 'ethereum',
           'merchant_id' => 0
    ];
}
