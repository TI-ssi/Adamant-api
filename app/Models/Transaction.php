<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class Transaction extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeConfirmationPending($query){
        return $query->where('blockNumber', '<=', (Setting::first()->last_synced_block - 12))
                     ->whereIn('status', ['pending', 'receipt_error'])
            ->where('type', 'ethereum_in');
    }
    
    public function scopePending($query){
        return $query->whereIn('status', ['pending', 'receipt_error']);
    }

    public function scopeLongPending($query){
        return $query->whereIn('status', ['pending', 'receipt_error'])->whereIn('type', ['ethereum_out_long_processing', 'ethereum_out_long']);
    }

    public function toWallet(){
        return $this->belongsTo('App\Models\Wallet', 'to', 'address');
    }

    public function fromWallet(){
        return $this->belongsTo('App\Models\Wallet', 'from', 'address');
    }

    public function sendingWallet(){
        return $this->belongsTo('App\Models\Wallet', 'sending_wallet', 'address');
    }

}
