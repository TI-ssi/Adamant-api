<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /*
        'Laravel\Passport\Events\AccessTokenCreated' => [
             'App\Listeners\PruneOldTokens',
         ],

         'Laravel\Passport\Events\RefreshTokenCreated' => [
             'App\Listeners\PruneOldTokens',
         ],*/

        'App\Events\RetrievedClient' => [
            'App\Listeners\UpdateClientStatus',
        ],
    ];
}
