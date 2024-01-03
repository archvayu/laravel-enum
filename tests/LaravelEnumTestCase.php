<?php

namespace ArchVayu\LaravelEnum\Tests;

use ArchVayu\LaravelEnum\Providers\LaravelEnumServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelEnumTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelEnumServiceProvider::class,
        ];
    }
}
