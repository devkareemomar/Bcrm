<?php

namespace App\Console\Commands;

class MakeCrudControllerCommand extends BaseCommand
{

    protected $signature = 'make:custom-crud-controller {module} {name}';

    protected $description = 'Generate crud controller';

    protected $module_path = '/Http/Controllers/Api/';


    public function handle()
    {
        $this->create($suffix = 'Controller', $stub_path = 'stubs/customs/crudController.stub');
    }
}
