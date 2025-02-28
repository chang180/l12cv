<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use App\View\Components\GuestLayout;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Blade::component('guest-layout', GuestLayout::class);
    }
}
