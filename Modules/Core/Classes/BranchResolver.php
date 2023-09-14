<?php

namespace Modules\Core\Classes;

class BranchResolver
{
    private $branch;

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    public function getBranch()
    {
        return $this->branch ?? null;
    }
}
