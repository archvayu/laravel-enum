<?php

namespace ArchVayu\LaravelEnum\Console\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;

class EnumMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:enum {name} {--type=} {--scalar=}";

    /**
     * type of generated object
     *
     * @var string
     */
    protected $type = 'Enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new enum Object';

    /**
     * Return the stub file path
     *
     * @return string
     */
    protected function getStub(): string
    {
        $type = $this->option('type');
        return __DIR__ . "/../../../stubs/enum-{$type}.stub";
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->components->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');

            return false;
        }

        if (!in_array($this->option('type'), ['pure', 'backed'])) {
            $this->components->error('The enum type is invalid. Use --type={pure|backed}');
            return false;
        }

        $type = $this->option('type');
        $scalar = $this->option('scalar');
        if ($type == 'backed' && $scalar) {
            if (!in_array($scalar, ['int', 'string'])) {
                $this->components->error('The scalar type is invalid.');
                return false;
            }
        } else {
            $this->components->error('The scalar type is not defined. Use --scalar={int|string}');
            return false;
        }

        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())
        ) {
            $this->components->error($this->type . ' already exists.');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $info = $this->type;

        if (in_array(CreatesMatchingTest::class, class_uses_recursive($this))) {
            if ($this->handleTestCreation($path)) {
                $info .= ' and test';
            }
        }

        if (windows_os()) {
            $path = str_replace('/', '\\', $path);
        }

        $this->components->info(sprintf('%s [%s] created successfully.', $info, $path));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceScalar($stub);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the scalar type for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceScalar($stub)
    {
        $scalar = $this->option('scalar');
        $stub = str_replace(['{{ scalar }}', '{{scalar}}'], $scalar, $stub);
        return $stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Enums";
    }
}
