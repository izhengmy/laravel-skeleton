<?php

namespace Override\Laravel\Illuminate\Database;

use Illuminate\Database\MigrationServiceProvider as ServiceProvider;
use Override\Laravel\Illuminate\Database\Migrations\MigrationCreator;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app['files']);
        });
    }
}
