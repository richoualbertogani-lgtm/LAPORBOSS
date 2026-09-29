<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_pages(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.kategori.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_open_dashboard(): void
    {
        Admin::create([
            'email_admin' => 'admin@laporboss.test',
            'password_admin' => Hash::make('admin123'),
        ]);

        $this->post(route('admin.login.store'), [
            'email_admin' => 'admin@laporboss.test',
            'password' => 'admin123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_admin_login_fails_with_wrong_password(): void
    {
        Admin::create([
            'email_admin' => 'admin@laporboss.test',
            'password_admin' => Hash::make('admin123'),
        ]);

        $this->post(route('admin.login.store'), [
            'email_admin' => 'admin@laporboss.test',
            'password' => 'salah',
        ])->assertSessionHasErrors('email_admin');

        $this->assertNull(session('admin_id'));
    }

    public function test_admin_can_log_out(): void
    {
        $this->withSession(['admin_id' => 1])
            ->post(route('admin.logout'))
            ->assertRedirect(route('home'));

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }
}
