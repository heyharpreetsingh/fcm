<?php

namespace Heyharpreetsingh\FCM\Providers;

use Illuminate\Support\ServiceProvider;
use Heyharpreetsingh\FCM\FCM;

class FCMServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            'hhs.fcm',
            function () {
                return new FCM;
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 
    }
}
