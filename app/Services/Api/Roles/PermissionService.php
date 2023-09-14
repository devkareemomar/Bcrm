<?php

namespace App\Services\Api\Roles;

use App\Services\Api\BaseApiService;

class PermissionService extends BaseApiService
{
    /**
     * get available permissions
     * 
     * @return App\Classes\JsonResponse
     */
    public function index()
    {
        $result = [];

        // get available modules from laratrust_seeder config under super_admin role 
        $modules = config('laratrust_seeder.app_roles_structure');

        // get permission map form laratrust_seeder config
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        // for each module get its permissions
        // [["name" => module , "submodules" => ["name"=>"","permissions" => [] ]]]
        $result = [];
        foreach ($modules as $module => $submodules) {
            // "selected" => false for frontend 
            $moduleData = ["name" => $module, "submodules" => [], "selected" => false];

            foreach ($submodules as $submodule => $permissionsString) {
                $submoduleData = ["name" => $submodule, "permissions" => [], "selected" => false];
                // stringify permission
                foreach (explode(',', $permissionsString) as $p => $perm) {
                    $permissionValue = $mapPermission->get($perm);
                    $submoduleData["permissions"][] = ["name" => $permissionValue, "selected" => false];
                }
                $moduleData["submodules"][] = $submoduleData;
            }

            $result[] = $moduleData;
        }

        return $this->jsonResponse()->setStatus(true)
            ->setCode(200)
            ->setResult($result);
    }
}
