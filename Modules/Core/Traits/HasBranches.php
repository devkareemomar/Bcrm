<?php

namespace Modules\Core\Traits;

use Modules\Core\Models\Branch;
use Modules\Core\Classes\BranchResolver;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasBranches
{
    public function branches(): BelongsToMany
    {

        return $this->belongsToMany(Branch::class, 'core_branch_user');
    }


    public function belongsToBranch($branch): bool
    {

        if ($this->branches->contains($branch) || $this->hasRole('super_admin')) {
            $this->setCurrentBranch($branch);
            return 1;
        }

        return 0;
    }

    public function defaultBranch(): bool
    {
        $branch = $this->hasRole('super_admin') ? Branch::first() : $this->branches()->first();

        if ($branch) {
            $this->setCurrentBranch($branch);
            return 1;
        }

        return 0;
    }

    private function setCurrentBranch($branch)
    {
        app(BranchResolver::class)->setBranch($branch);
        $this->current_branch_id = (int)$branch->id;
        $this->save();
    }
}
