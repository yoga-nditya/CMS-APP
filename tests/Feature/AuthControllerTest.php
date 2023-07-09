<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testLoginPage()
    {
        $this->get('/login')->assertSeeText('Silahkan Login');
    }

    public function testLoginSucsessForAdmin()
    {
        // Unit Test Jika Admin Sudah Login Maka Redirect ke Halaman dashboard
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '123'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/admin/dashboard');
    }

    // public function testLoginSucsessForCashier()
    // {
    //     // Unit Test Jika Cashier Sudah Login Redirect ke Halaman Order 
    //     User::factory()->create([
    //         'name' => 'kasir',
    //         'email' => 'kasir@gmail.com',
    //         'password' => bcrypt('123'),
    //         'role' => 'kasir'
    //     ]);

    //     // Unit Test Post Login 
    //     $response = $this->post('/login', [
    //         'email' => 'kasir@gmail.com',
    //         'password' => '123'
    //     ]);
    //     $response->assertStatus(302);
    //     $response->assertRedirect('/cashier/order');
    // }

    public function test_if_admin_not_login_cannot_access_admin_dasboard()
    {
        // Unit Test Jika Admin belum login maka tidak boleh aksess halaman dashboard
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    
    public function test_if_cashier_not_login_cannot_access_cashier_order()
    {
        // Unit Test Jika Cashier belum login maka tidak boleh aksess halaman order
        $response = $this->get('/cashier/order');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

   public function testValidationError()
   {
        $response = $this->post('/login', []);
        $response->assertStatus(302);
        $response->assertRedirect('/');
   }

   public function testLoginForAdminAlreadyLogin()
   {
        $this->withSession([
            "name" => "admin"
        ])->post('/login', [
            "email" => "admin@gmail.com",
            "password" => "123"
        ])->assertRedirect("/admin/dashboard")->assertSessionHas("name","admin");
    }

}
