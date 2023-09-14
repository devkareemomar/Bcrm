<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Facades\Artisan;

class MakeFullCrudCommand extends Command
{

    protected $signature = 'make:full-crud {module} {name}';

    protected $description = 'Generate full crud';

    public function handle()
    {
        Artisan::call("module:make-request", ['module' => $this->argument('module'), 'name' => ucwords(Pluralizer::plural($this->argument('name'))) . '/Store' . $this->argument('name') . 'Request']);
        Artisan::call("module:make-request", ['module' => $this->argument('module'), 'name' => ucwords(Pluralizer::plural($this->argument('name'))) . '/Update' . $this->argument('name') . 'Request']);
        Artisan::call("make:custom-resource", ['module' => $this->argument('module'), 'name' => $this->argument('name')]);
        Artisan::call("make:custom-repository", ['module' => $this->argument('module'), 'name' => $this->argument('name')]);
        Artisan::call("make:custom-crud-controller", ['module' => $this->argument('module'), 'name' => $this->argument('name')]);
        $this->info("🤓🤓🤓🤓");
    }
}
