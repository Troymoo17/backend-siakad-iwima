<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NotificationService;
use App\Services\PushNotificationService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PushNotificationService::class, function ($app) {
            return new PushNotificationService();
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService($app->make(PushNotificationService::class));
        });
    }

    public function boot(): void
    {
        //
    }
}