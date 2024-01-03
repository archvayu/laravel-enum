<?php

namespace ArchVayu\LaravelEnum\Providers;

use Illuminate\Support\ServiceProvider;
use ArchVayu\LaravelEnum\Console\Commands\EnumMakeCommand;

class LaravelEnumServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    EnumMakeCommand::class
                ]
            );
        }
    }
}
