<?php

namespace App\Providers;

use App\Models\User;

use App\Models\UserInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Dusterio\LumenPassport\LumenPassport as LumenPassport;
use Laravel\Passport\Passport as Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        LumenPassport::routes($this->app);

        Passport::tokensExpireIn(Carbon::now()->addMinute());

        Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(60));

    }
}
