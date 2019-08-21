<?php

namespace Override\Laravel\Illuminate\Foundation\Console;

use Illuminate\Foundation\Console\ResourceMakeCommand as Command;

class ResourceMakeCommand extends Command
{
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->collection()
            ? __DIR__.'/stubs/resource-collection.stub'
            : __DIR__.'/stubs/resource.stub';
    }
}
