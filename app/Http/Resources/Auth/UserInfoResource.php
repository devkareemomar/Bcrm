<?php

namespace App\Http\Resources\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Branches\BranchBriefResource;
use Modules\Core\Models\Branch;
use Modules\Core\Repositories\Branches\BranchRepositoryInterface;
use Modules\Core\Repositories\Companies\CompanyRepositoryInterface;

class UserInfoResource extends JsonResource
{
    /**
     * Transform user resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $branches   = $this->hasRole('super_admin') ? Branch::all() : $this->branches;
        $branch_id  = $this->current_branch_id ?? $this->defaultBranch();
        $company_id = app(BranchRepositoryInterface::class)->findById($branch_id, fields: ['id', 'company_id'])->company_id  ?? false;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo' => isset($this->photo) ? asset(Storage::url($this->photo)) : null,
            'permissions' => $this->allPermissions()->pluck('name'),
            'current_branch_id' => (int)$branch_id,
            'current_company_id' => $company_id,
            'branches' => BranchBriefResource::collection($branches),
        ];
    }
}

