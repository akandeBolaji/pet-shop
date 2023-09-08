<?php

namespace App\Providers;

use Auth;
use App\Libraries\JWTGuard;
use App\Libraries\JWTLibraryClient;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use InvalidArgumentException;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('jwt', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);

            if ($provider !== null) {
                return new JWTGuard($provider, $app->make('request'), $app->make(JWTLibraryClient::class));
            }

            throw new InvalidArgumentException('UserProvider cannot be null');
        });
    }
}
