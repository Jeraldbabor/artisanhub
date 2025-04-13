<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $unreadNotifications = auth()->user()->notifications()
                    ->whereNull('read_at')
                    ->count();
                
                $view->with('unreadNotificationsCount', $unreadNotifications);
            }
        });
    }
}
