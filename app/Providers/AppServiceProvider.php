<?php

namespace Digist\Providers;

use Api\Support\Settings;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Digist\Services\NemId\Service as NemIdService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(DuskServiceProvider::class);
        }

        $this->app->singleton(NemIdService::class, function () {
            return new NemIdService();
        });

        $this->app->singleton('settings', function () {
            return new Settings();
        });
    }
}
