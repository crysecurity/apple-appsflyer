<?php

namespace Cr4sec\AppleAppsFlyer;

use Cr4sec\AppleAppsFlyer\Models\Purchase;
use Cr4sec\AppleAppsFlyer\Models\Receipt;
use Cr4sec\AppleAppsFlyer\Observers\PurchaseObserver;
use Cr4sec\AppleAppsFlyer\Observers\ReceiptObserver;
use Illuminate\Support\ServiceProvider;

class AppleAppsFlyerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/apple-appsflyer.php' => config_path('apple-appsflyer.php'),
            ], 'apple-appsflyer-config');
        }

        Purchase::observe(PurchaseObserver::class);
        Receipt::observe(ReceiptObserver::class);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AppsFlyer', static function () {
            return new Client();
        });

        $this->app->alias('AppsFlyer', Client::class);
    }
}
