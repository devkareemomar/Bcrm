<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cms\Database\Seeders\SeoSettingSeederTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // call laratrust seeder
        $this->call(LaratrustSeeder::class);

        // call user seeder
        $this->call(UserSeeder::class);

        // call seo setting seeder
        $this->call(SeoSettingSeederTableSeeder::class);


    }
}
