<?php

namespace Modules\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Cms\Models\SeoSetting;

class SeoSettingSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_seo_settings')->truncate();
        $seoSettong  = [
                0 => [
                    'key' => 'site_map',
                    'value' => 'storage/site-map.xml',
                ],
                1 => [
                    'key' => 'robot',
                    'value' => 'storage/robot.txt',
                ],
                2 => [
                    'key' => 'custom_code_head',
                    'value' => null,
                ],
                3 => [
                    'key' => 'custom_code_footer',
                    'value' => null,
                ],
                4 => [
                    'key' => 'custom_code_body',
                    'value' => null,
                ],

                ];

        foreach($seoSettong as $seo){
            SeoSetting::create($seo);
        }
    }

}
