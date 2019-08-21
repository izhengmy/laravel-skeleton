<?php

namespace App\Providers;

use App\Support\SmsCaptcha\SmsCaptcha;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerIdeHelperServiceProvider();

        $this->app->singleton(SmsCaptcha::class, SmsCaptcha::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('china_mobile_number', 'App\Support\Rules\ChinaMobileNumber@passes');
        Validator::extend('unsigned', 'App\Support\Rules\Unsigned@passes');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    protected function registerIdeHelperServiceProvider()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
