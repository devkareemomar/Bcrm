<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class BaseImport implements  ToCollection
{

    public $modelName;
    public $branchId;
    public $prifix;
    public $code;

    public function __construct($modelName,$branchId,$prifix,$code)
    {
        $this->modelName = $modelName;
        $this->branchId = $branchId;
        $this->prifix = $prifix;
        $this->code = $code;
    }



    public function collection(Collection $rows)
    {
        foreach ($rows->slice(1) as  $row)
        {
            $values = array_combine($rows[0]->toArray(),$row->toArray());
            $code = $this->code++;
            $values['code'] = $this->prifix . $code;
            $values['branch_id'] = $this->branchId;

            $this->modelName::create($values);
        }
    }

}
