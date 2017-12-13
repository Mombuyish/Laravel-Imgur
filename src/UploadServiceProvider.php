<?php

namespace Yish\Imgur;

use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/imgur.php' => config_path('imgur.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     */
    public function register()
    {
        $this->app->bind(Upload::class, function ($app) {
            $client_id = $app['config']->get('imgur.client_id');
            $client_secret = $app['config']->get('imgur.client_secret');

            return new Upload($client_id, $client_secret);
        });
    }
}
