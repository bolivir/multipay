<?php

namespace Bolivir\Multipay;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'multipay'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('multipay.php'),
        ], 'config');
    }
}
