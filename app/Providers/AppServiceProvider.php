<?php

namespace App\Providers;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

// Correct namespace

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $clientId   = config('services.apple.client_id');
        $teamId     = config('services.apple.team_id');
        $keyId      = config('services.apple.key_id');
        $privateKey = trim(config('services.apple.private_key'));

        $now = Carbon::now()->timestamp;
        $exp = Carbon::now()->addMonths(6)->timestamp;

        $payload = [
            'iss' => $teamId, // Your Apple Team ID
            'iat' => $now, // Issued at
            'exp' => $exp, // Expiration time
            'aud' => 'com.evento.aionprojectAp',
            'sub' => $clientId, // Your Apple Client ID
        ];

        // Generate the JWT for client_secret with ES256, using the Key ID in the header.
        $clientSecret = JWT::encode($payload, $privateKey, 'ES256', $keyId);

        // Inject the generated client secret into configuration
        config()->set('services.apple.client_secret', $clientSecret);

        // Register the Apple provider
        $this->app['events']->listen(SocialiteWasCalled::class, function ($event) {
            $event->extendSocialite('apple', \SocialiteProviders\Apple\Provider::class);
        });
    }
}
