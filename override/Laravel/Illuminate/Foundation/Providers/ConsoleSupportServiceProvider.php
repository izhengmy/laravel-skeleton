<?php

namespace Override\Laravel\Illuminate\Foundation\Providers;

use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Foundation\Providers\ConsoleSupportServiceProvider as ServiceProvider;
use Override\Laravel\Illuminate\Database\MigrationServiceProvider;

class ConsoleSupportServiceProvider extends ServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
