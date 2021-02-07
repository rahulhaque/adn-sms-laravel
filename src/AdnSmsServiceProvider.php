<?php

namespace RahulHaque\AdnSms;

use Illuminate\Support\ServiceProvider;

class AdnSmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/adn-sms.php' => base_path('config/adn-sms.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/adn-sms.php', 'adn-sms');

        $this->app->bind('adn-sms', AdnSms::class);
    }
}
