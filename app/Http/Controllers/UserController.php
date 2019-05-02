<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;

use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{
     public function getAuthUser(){
         if(!Auth::User()) return $this->error('client_not_found', 404);
         else return $this->success(User::with('roles')->find(Auth::User()->id));
    }

    public function createUser(Request $request){

        //validate input

        //check if he already exist
        $exist = User::where('username', $request->username)->first();

        if($exist) return $this->error();
        
        //create the client
        $newClient = new User;

        $newClient->username = $request->username;
        $newClient->password = password_hash($request->password, PASSWORD_DEFAULT);


        $newClient->save();

        
        //create a user instance


        return $this->success($newClient->client_id);
    }

    public function updateUser(Request $request){

        //validate input

   
        //update the client

        if($request->nickname != Auth::User()->nickname){
          //check nickname is already taken
          $exist = User::where('nickname', $request->nickname)->first();

          if($exist) return $this->error('nickname_taken');

        
          Auth::User()->nickname = trim($request->nickname);
        }

        Auth::User()->save();

        return $this->success(Auth::User()->client_id);
    }



}
