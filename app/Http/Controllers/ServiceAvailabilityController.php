<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Setting;

class ServiceAvailabilityController extends BaseController
{
    public function getResponse(){
         return $this->success(['message' => 'This is a Get response.']);
    }

    public function postResponse(){
         return $this->success(['message' => 'This is a Post response.']);
    }

    public function getAuthResponse(){
         return $this->success(['message' => 'This is an authenticated Get response.']);
    }

    public function postAuthResponse(){
         return $this->success(['message' => 'This is an authenticated Post response.']);
    }

    
}
