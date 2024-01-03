<?php

use ArchVayu\LaravelEnum\Console\Commands\EnumMakeCommand;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\assertTrue;

it('can run the command successfully', function () {
    $this
        ->artisan(EnumMakeCommand::class, ['name' => 'Test'])
        ->assertSuccessful();
});

it('generate enum when called', function (string $class) {
    $this
        ->artisan(
            EnumMakeCommand::class,
            ['name' => $class]
        )->assertSuccessful();

    assertTrue(
        File::exists(
            path: app_path("Enums/$class.php")
        )
    );
})->with('classes');
