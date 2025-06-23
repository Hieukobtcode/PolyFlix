<?php

namespace App\Providers;

use App\Models\AdminRequest;
use Illuminate\Support\Facades\View;
use App\Http\Middleware\AuthRedirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckAdminAccess;
use App\Models\RapPhim;
use Carbon\Carbon;

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
        Route::aliasMiddleware('permission.check', CheckPermission::class);
        Carbon::setLocale('vi');
    }
}