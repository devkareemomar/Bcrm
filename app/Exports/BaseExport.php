<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class BaseExport implements   FromCollection , WithHeadings ,ShouldAutoSize
{

    public $data;
    public $fields;
    public function __construct($data,$fields)
    {
        $this->data = $data;
        $this->fields = $fields;
    }

    public function headings(): array
    {
        return $this->fields ;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;

    }

}
