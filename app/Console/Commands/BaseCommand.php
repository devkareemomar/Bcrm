<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;

abstract class BaseCommand extends Command
{
    protected $files;

    protected $module_path;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }


    protected function create($suffix, $stub_path)
    {
        $path = $this->getSourceFilePath($suffix);

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile($stub_path);

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }

    
    protected function getSourceFilePath($suffix = '')
    {
        return base_path('Modules') . '/' . $this->argument('module') . $this->module_path . $this->getPlurarClassName($this->argument('name')) . '/' . $this->argument('name')  . $suffix . '.php';
    }


    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }


    protected function getSourceFile($stub_path)
    {
        return $this->getStubContents($this->getStubPath($stub_path), $this->getStubVariables());
    }


    protected function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('{{' . $search . '}}', $replace, $contents);
        }

        return $contents;
    }


    protected function getStubPath($stub_path)
    {
        return base_path($stub_path);
    }


    protected function getStubVariables()
    {
        return [
            'namespace'     => 'Modules\\' . $this->argument('module') . $this->getNamespace() . $this->getPlurarClassName($this->argument('name')),
            'upper_name'        => $this->argument('name'),
            'lower_name'        => strtolower($this->argument('name')),
            'plurar_upper_name'      => $this->getPlurarClassName($this->argument('name')),
            'plurar_lower_name'      => strtolower($this->getPlurarClassName($this->argument('name'))),
            'upper_module'        => $this->argument('module'),
            'lower_module'      => strtolower($this->argument('module'))
        ];
    }


    protected function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    protected function getPlurarClassName($name)
    {
        return ucwords(Pluralizer::plural($name));
    }

    protected function getNamespace()
    {
        return str_replace("/", "\\", $this->module_path);
    }
}
