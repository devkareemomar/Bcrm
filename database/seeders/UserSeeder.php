<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('users')->truncate();

        $user = User::create([
            'name' => 'super admin',
            'email' => 'super_admin@example.com',
            'password' => bcrypt('123456')
        ]);

        $user->attachRole('super_admin');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
