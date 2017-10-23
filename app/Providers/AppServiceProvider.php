<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \Blade::if('admin', function() {
             if(auth()->check()) {
                 if(auth()->user()->role == "admin" || auth()->user()->role == "super_admin") {
                     return true;
                 }
             }
        });

        \Blade::if('isLoggedIn', function() {
            if(auth()->check()) {
                return true;
            }
            return false;
        });

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
