<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BaseExampleExport implements WithHeadings ,ShouldAutoSize
{

    public $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function headings(): array
    {
        return $this->fields ;
    }

}
