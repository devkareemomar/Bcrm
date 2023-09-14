<?php

namespace App\Exports\Users;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Repositories\Users\UserRepositoryInterface;

class UserExport implements FromCollection, WithHeadings
{

    public $role_id;
    protected UserRepositoryInterface $userRepository;

    public function __construct($role_id)
    {
        $this->userRepository = app()->make(UserRepositoryInterface::class);
        $this->role_id = $role_id;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Email",
            "Created Date",
            "Role"
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->userRepository->getAllUsersByRole($this->role_id);
    }
}
