<?php

namespace App\Providers;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register the Http application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('http', function () {
            return new Factory();
        });
        $this->app->alias('http', Http::class);
    }
}
