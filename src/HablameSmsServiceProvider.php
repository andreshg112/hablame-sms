<?php

namespace Andreshg112\HablameSms;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class HablameSmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('hablame-sms', function () {
            /** @var \Closure $callback */
            $callback = Config::get('services.hablame_sms.guzzle');

            /** @var \GuzzleHttp\Client|null $http */
            $http = isset($callback) ? $callback() : null;

            return new Client(
                Config::get('services.hablame_sms.account'),
                Config::get('services.hablame_sms.apikey'),
                Config::get('services.hablame_sms.token'),
                $http
            );
        });
    }
}
