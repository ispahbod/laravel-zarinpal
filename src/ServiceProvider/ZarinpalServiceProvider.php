<?php

namespace Ispahbod\Zarinpal\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Ispahbod\Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/zarinpal.php', 'zarinpal');
        $this->app->bind("Zarinpal", function () {
            return new Zarinpal();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/zarinpal.php' => config_path('zarinpal.php')
        ], 'zarinpal-config');
    }
}