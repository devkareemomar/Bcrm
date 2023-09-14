<?php

namespace App\Console\Commands;


class MakeResourceCommand extends BaseCommand
{
    protected $signature = 'make:custom-resource {module} {name}';

    protected $description = 'Generate resource';

    protected $module_path = '/Http/Resources/';

    public function handle()
    {
        $this->create($suffix = 'Resource', $stub_path = 'stubs/customs/resource.stub');
        $this->create($suffix = 'Collection', $stub_path = 'stubs/customs/resourceCollection.stub');
        $this->create($suffix = 'BriefResource', $stub_path = 'stubs/customs/briefResource.stub');
    }
}
