<?php

namespace Override\Laravel\Illuminate\Routing\Console;

use Illuminate\Routing\Console\ControllerMakeCommand as Command;

class ControllerMakeCommand extends Command
{
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.strstr(parent::getStub(), '/stubs');
    }
}
