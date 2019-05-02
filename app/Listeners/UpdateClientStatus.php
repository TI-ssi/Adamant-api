<?php

namespace App\Listeners;

use App\Events\RetrievedClient;

use Illuminate\Support\Facades\DB;

use Log;

class UpdateClientStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle($event)
    {
        $client_status = $event->client->profile_status;

        ///seems like 'Complet' profile status screw the product status...
        if ($event->client->profile_status == 'Incomplet') {
            $client_status = $event->client->profile_status.' - '.$event->client->product_status;
        }

        if ($event->client->client_getclientstatus != $client_status) {
            Log::info("Client {$event->client->client_id} status update ({$event->client->client_getclientstatus} => {$client_status}).");

            $event->client->client_getclientstatus = $client_status;
            $event->client->save();

            ///not even sure it's useful...
            DB::update("UPDATE client_dailystats SET needrehash='1'");
       }
    }
}
