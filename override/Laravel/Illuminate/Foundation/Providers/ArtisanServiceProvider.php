<?php

namespace Override\Laravel\Illuminate\Foundation\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider as ServiceProvider;
use Override\Laravel\Illuminate\Foundation\Console\ModelMakeCommand;
use Override\Laravel\Illuminate\Foundation\Console\ResourceMakeCommand;
use Override\Laravel\Illuminate\Routing\Console\ControllerMakeCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerControllerMakeCommand()
    {
        $this->app->singleton('command.controller.make', function ($app) {
            return new ControllerMakeCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new ModelMakeCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerResourceMakeCommand()
    {
        $this->app->singleton('command.resource.make', function ($app) {
            return new ResourceMakeCommand($app['files']);
        });
    }
}
