<?php

namespace Modules\Cms\Repositories\Setting;

use Illuminate\Support\Facades\DB;
use App\Repositories\BaseEloquentRepository;
use Modules\Cms\Http\Resources\Setting\SeoSettingResource;
use Modules\Cms\Models\SeoSetting;

class SeoSettingRepository extends BaseEloquentRepository implements SeoSettingRepositoryInterface
{
    /** @var string */
    protected $modelName = SeoSetting::class;



    /**
     * update model instance
     *
     * @param object $instance model instance
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function updateByInstance($instance, array $data)
    {
        $this->instance = $instance;

        // start transaction update seo setting  or nothing at all
        DB::beginTransaction();
        try {

            $seo = $this->executeSave($data);
            // commit changes
            DB::commit();

            return $seo;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }



    /**
     * get all users by role for data export
     *
     * @param int|null $role_id
     * @return mixed
     * @throws \Exception
     */
    public function getAllSeoSetting()
    {
        $query =  DB::table('cms_seo_settings')
                    ->select(['key', 'value'])
                    ->get();

        return $query;
    }
}
