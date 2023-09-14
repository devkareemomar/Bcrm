<?php

use Modules\Core\Classes\BranchResolver;

if (!function_exists('current_branch_id')) {

    function current_branch_id()
    {
        return auth()->user()->current_branch_id ?? null;
    }

    function current_branch()
    {
        return app(BranchResolver::class)->getBranch();
    }
}
