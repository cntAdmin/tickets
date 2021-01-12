<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand as ConsoleModelMakeCommand;

class ModelMakeCommand extends ConsoleModelMakeCommand
{
    protected function getDefaultNamespace($rootNamespace) {
        return "{$rootNamespace}\Models";
    }
}
