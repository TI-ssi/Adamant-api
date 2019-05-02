<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Support\Facades\App;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    /**
     * Check the password for the user.
     *
     * @return bool
     */
    public function validateForPassportPasswordGrant($password){
        return password_verify($password, $this->password);
    }

    /**
     * Find the user for passport
     *
     * @return User
     */    
    public function findForPassport($username){
        return $this->where('username', $username)->first();
    }


    public function roles(){
        return $this->belongsToMany('App\Models\Role')
                    ->withTimestamps();
    }

    public function wallets(){
        return $this->hasMany('App\Models\Wallet');
    }

}
