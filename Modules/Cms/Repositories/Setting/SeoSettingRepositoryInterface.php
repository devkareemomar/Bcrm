<?php


namespace Modules\Cms\Repositories\Setting;

use App\Interfaces\BaseEloquentRepositoryInterface;

interface SeoSettingRepositoryInterface extends BaseEloquentRepositoryInterface
{
    public function getAllSeoSetting();
}
