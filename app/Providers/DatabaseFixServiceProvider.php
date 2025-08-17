<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Database\LostConnectionDetector as LostConnectionDetectorContract;

class DatabaseFixServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Override the LostConnectionDetector binding
        $this->app->singleton(LostConnectionDetectorContract::class, function ($app) {
            return new class implements LostConnectionDetectorContract {
                public function causedByLostConnection(\Throwable $e): bool
                {
                    $message = $e->getMessage();

                    $patterns = [
                        'server has gone away',
                        'no connection to the server',
                        'Lost connection',
                        'is dead or not enabled',
                        'Error while sending',
                        'decryption failed or bad record mac',
                        'server closed the connection unexpectedly',
                        'SSL connection has been closed unexpectedly',
                        'Error writing data to the connection',
                        'Resource deadlock avoided',
                        'Transaction() on null',
                        'child connection forced to terminate due to client_idle_limit',
                        'query_wait_timeout',
                        'reset by peer',
                        'Physical connection is not usable',
                        'TCP Provider: Error code 0x68',
                        'Name or service not known',
                        'ORA-03114',
                        'Packets out of order. Expected',
                        'Adaptive Server connection failed',
                        'Communication link failure',
                        'connection is no longer usable',
                        'Login timeout expired',
                        'SQLSTATE[HY000] [2002]',
                        'SQLSTATE[HY000] [2006]',
                    ];

                    foreach ($patterns as $pattern) {
                        if (str_contains($message, $pattern)) {
                            return true;
                        }
                    }

                    return false;
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
