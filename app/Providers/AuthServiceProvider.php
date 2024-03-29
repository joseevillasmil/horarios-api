<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Route::prefix('{client}')->group(function() {
            Route::middleware(['client'])->group(function () {
                Passport::routes(function ($router) {
                    $router->forAccessTokens();
                });
            });
        });
        Passport::tokensExpireIn(Carbon::now()->addDays(365));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(365));

        //
    }
}
