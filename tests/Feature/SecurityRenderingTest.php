<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SecurityRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_supplied_name_is_escaped_and_locked_from_translation(): void
    {
        $name = '<script>alert("xss-name")</script>';

        $user = User::create([
            'nis' => '3999',
            'nama' => $name,
            'email' => 'security-render@example.test',
            'otp_enabled' => false,
            'password' => 'secret123',
            'level' => 3,
            'kelas' => null,
        ]);

        $response = $this->withSession([
            'logged_in' => true,
            'user' => [
                'id_user' => $user->id_user,
                'nis' => $user->nis,
                'nama' => $user->nama,
                'email' => $user->email,
                'otp_enabled' => false,
                'level' => 3,
                'role_label' => 'Superadmin',
            ],
        ])->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee($name, false);
        $response->assertSee(e($name), false);
        $response->assertSee('sidebar-user-name notranslate', false);
        $response->assertSee('translate="no"', false);
    }

    public function test_html_tags_are_removed_from_submitted_text_inputs(): void
    {
        $superadmin = User::create([
            'nis' => '3000',
            'nama' => 'Superadmin',
            'email' => 'superadmin-security@example.test',
            'otp_enabled' => false,
            'password' => 'secret123',
            'level' => 3,
            'kelas' => null,
        ]);

        $response = $this->withSession([
            'logged_in' => true,
            'user' => [
                'id_user' => $superadmin->id_user,
                'nis' => $superadmin->nis,
                'nama' => $superadmin->nama,
                'email' => $superadmin->email,
                'otp_enabled' => false,
                'level' => 3,
                'role_label' => 'Superadmin',
            ],
        ])->post(route('superadmin.users.store'), [
            'nama' => '<b>Hendra Huang</b>',
            'email' => 'hendra-security@example.test',
            'nis' => '<i>12345</i>',
            'level' => 1,
            'password' => 'secret123',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'nama' => 'Hendra Huang',
            'nis' => '12345',
            'email' => 'hendra-security@example.test',
        ]);
        $this->assertSame(0, DB::table('users')->where('nama', 'like', '%<b>%')->count());
    }
}
