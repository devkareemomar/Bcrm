<?php

namespace App\Console\Commands;

class MakeRepositoryCommand extends BaseCommand
{

    protected $signature = 'make:custom-repository {module} {name}';

    protected $description = 'Generate repository';

    protected $module_path = '/Repositories/';


    public function handle()
    {
        $this->create($suffix = 'Repository', $stub_path = 'stubs/customs/repository.stub');
        $this->create($suffix = 'RepositoryInterface', $stub_path = 'stubs/customs/repositoryInterface.stub');
    }
}
