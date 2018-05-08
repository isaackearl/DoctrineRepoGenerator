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
    protected $signature = 'doctrine:make:repository {name}';

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
        $this->repoName = $this->normalizeName($this->argument('name'));
        $this->makeRepositoryInterface();
        $this->makeDoctrineRepository();
        $this->generateServiceProvider();
        $this->composer->dumpAutoloads();
    }

    /**
     * Get the class name for the Eloquent model generator.
     *
     * @param $name
     * @return string
     */
    protected function normalizeName($name)
    {
        return ucwords(str_singular(camel_case($name)));
    }

    /**
     * Generate the desired repository interface.
     */
    protected function makeRepositoryInterface()
    {
        if ($this->files->exists($path = $this->getInterfacePath($this->repoName))) {
            $this->error('Interface already exists!');
            return;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->compileInterfaceStub());
        $this->info('Repository Interface created successfully.');
    }

    /**
     * Generate the desired repository interface.
     */
    protected function generateServiceProvider()
    {
        if ($this->files->exists($path = $this->getServiceProviderPath())) {
            $this->warn('Service provider already exists.  You must add this repository to the service provider manually.');
            $this->warn('Register the repository here: ' . $this->getAppNamespace() . 'Repositories\\Providers\\RepositoryServiceProvider.php');
            return;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->compileProviderStub());
        $this->info('Service Provider created successfully.');
        $this->warn('Don\'t forget to add the service provider to app.php with this line:');
        $this->warn($this->getAppNamespace() . 'Repositories\\Providers\\RepositoryServiceProvider::class');
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getInterfacePath($name)
    {
        return base_path() . '/app/Repositories/Interfaces/' . $name . 'Repository.php';
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @return string
     */
    protected function getServiceProviderPath()
    {
        return base_path() . '/app/Repositories/Providers/RepositoryServiceProvider.php';
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

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileInterfaceStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/interface.stub');
        $stub = $this->replaceClassName($stub);
        $stub = $this->replaceNamespace($stub);
        return $stub;
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileProviderStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/serviceprovider.stub');
        $stub = $this->replaceClassName($stub);
        $stub = $this->replaceNamespace($stub);
        return $stub;
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return string
     */
    protected function replaceClassName(&$stub)
    {
        return str_replace('{{name}}', $this->repoName, $stub);
    }

    /**
     * @param string $stub
     * @return string
     */
    protected function replaceNamespace($stub)
    {
        return str_replace('{{namespace}}', $this->getAppNamespace(), $stub);
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
     * Generate the desired doctrine repository.
     */
    protected function makeDoctrineRepository()
    {
        if ($this->files->exists($path = $this->getDoctrinePath($this->repoName))) {
            $this->error('Repository already exists!');
            return;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->compileRepositoryStub());
        $this->info('Doctrine Repository created successfully.');
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getDoctrinePath($name)
    {
        return base_path() . '/app/Repositories/Doctrine/Doctrine' . $name . 'Repository.php';
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileRepositoryStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/repository.stub');
        $stub = $this->replaceClassName($stub);
        $stub = $this->replaceNamespace($stub);
        return $stub;
    }

}