<?php

namespace App\Providers;

use App\Http\Middleware\AuthRedirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckAdminAccess;

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
        //
        Route::aliasMiddleware('admin.access', CheckAdminAccess::class);
        Route::aliasMiddleware('custom.auth', AuthRedirect::class);
    
        // parent::boot();
    }
}
