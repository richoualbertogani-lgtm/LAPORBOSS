<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_register_and_log_in_with_nis(): void
    {
        $this->post(route('register.store'), [
            'nis' => '123456789',
            'nama' => 'Siswa Contoh',
            'rombel' => 'XI RPL 1',
        ])->assertRedirect(route('home'))
            ->assertSessionHas('user_id', 123456789);

        $this->assertDatabaseHas('users', [
            'nis' => 123456789,
            'nama' => 'Siswa Contoh',
            'rombel' => 'XI RPL 1',
        ]);

        $this->post(route('logout'));

        $this->post(route('login.store'), ['nis' => '123456789'])
            ->assertRedirect(route('home'))
            ->assertSessionHas('user_id', 123456789);
    }
}
