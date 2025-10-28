<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_dashboard_when_authenticated(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $this->actingAs($user, 'web');
        $this->get('/admin')->assertOk()->assertSee('Dashboard');
    }
}







