<?php
/**
 * Created by PhpStorm.
 * User: iearl
 * Date: 5/7/18
 * Time: 1:11 AM
 */

namespace IsaacKenEarl\DoctrineRepoGenerator\Commands;


use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class RepositoryMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctrine:make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a Doctrine repository';

    /**
     * @var string
     */
    private $repoName;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var Filesystem
     */
    private $files;

    /**
     * @var string
     */
    private $type = 'Repository';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = app()['composer'];
    }

    /**
     * Alias for the fire method.
     *
     * In Laravel 5.5 the fire() method has been renamed to handle().
     * This alias provides support for both Laravel 5.4 and 5.5.
     */
    public function handle()
    {
        $this->fire();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->repoName = $this->argument('name');
        $this->makeRepositoryInterface();
        $this->makeDoctrineRepository();
//        $this->makeRepository();
//        $this->generateServiceProvider();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }

    /**
     * Generate the desired repository.
     */
    protected function makeRepositoryInterface()
    {
        $name = $this->argument('name');
        if ($this->files->exists($path = $this->getInterfacePath($name))) {
            $this->error($this->type . ' already exists!');
            return;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->compileInterfaceStub());
        $this->info('Repository created successfully.');
        $this->composer->dumpAutoloads();
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileMigrationStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/migration.stub');
        $this->replaceClassName($stub)
            ->replaceSchema($stub)
            ->replaceTableName($stub);
        return $stub;
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getInterfacePath($name)
    {
        return base_path() . '/app/Repositories/Interfaces' . $name . '.php';
    }

    /**
     * Get the application namespace.
     *
     * @return string
     */
    protected function getAppNamespace()
    {
        return Container::getInstance()->getNamespace();
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
}