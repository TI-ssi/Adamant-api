<?php

namespace App\Events;

use App\Models\ClientProfile;

class RetrievedClient extends Event
{
    public $client;

    public function __construct(ClientProfile $client){
        $this->client = $client;
    }
}
