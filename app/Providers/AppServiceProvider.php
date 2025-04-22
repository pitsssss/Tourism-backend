<?php

namespace App\Providers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Request;

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
    RateLimiter::for('login', function ($request) {
        return Limit::perMinute(5)->by($request->ip());
    });

    RateLimiter::for('contact', function ($request) {
        return Limit::perMinute(3)->by($request->ip());
    });
    RateLimiter::for('register', function (Request $request) {
        return Limit::perMinutes(10, 5)->by($request->ip());
    });

    }
}
