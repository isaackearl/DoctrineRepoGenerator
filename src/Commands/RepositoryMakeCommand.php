<?php
/**
 * Created by PhpStorm.
 * User: iearl
 * Date: 5/7/18
 * Time: 1:11 AM
 */

namespace IsaacKenEarl\DoctrineRepoGenerator\Commands;


use Illuminate\Console\GeneratorCommand;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctrine:make:repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

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
        $this->makeRepository();
        $this->generateServiceProvider();
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
}