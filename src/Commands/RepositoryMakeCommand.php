<?php
/**
 * Created by PhpStorm.
 * User: iearl
 * Date: 5/7/18
 * Time: 1:11 AM
 */

namespace IsaacKenEarl\DoctrineRepoGenerator\Commands;


use Illuminate\Console\Command;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('hey');
    }
}