<?php

namespace IsaacKenEarl\DoctrineGenerator;

use Illuminate\Support\ServiceProvider;

class DoctrineGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositoryGenerator();
    }

    private function registerRepositoryGenerator()
    {
        $this->app->singleton('command.isaackenearl.repo', function ($app) {
            return $app['IsaacKenEarl\DoctrineGenerator\Commands\RepositoryMakeCommand'];
        });

        $this->commands('command.isaackenearl.repo');
    }
}