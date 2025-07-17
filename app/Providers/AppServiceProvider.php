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
use Illuminate\Support\Facades\Schema;

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

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
        try {
            // Only load data if table exists (not during migrations)
            if (Schema::hasTable('rap_phims')) {
                $rapPhims = RapPhim::all()->groupBy('chi_nhanh_id');
                View::share('rapPhims', $rapPhims);
            }
        } catch (\Exception $e) {
            // Ignore database errors during migration/setup
        }
    }
}
