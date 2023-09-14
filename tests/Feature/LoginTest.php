<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Inventory\Models\Unit;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */




    public function test_login()
    {
        $data = ['email' => 'super_admin@example.com', 'password' => '123456'];
        $this->post('/api/v1/auth/login', $data)
            ->assertStatus(200);
    }

    public function test_information_method()
    {
        $this->login();
        $this->get('/api/v1/auth/info')
            ->assertStatus(200);
    }


    public function  login()
    {
        $user = User::first();
        $this->actingAs($user);
    }
}
