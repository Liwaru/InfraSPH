<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperadminUserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_role_cannot_be_created_from_user_form(): void
    {
        $admin = $this->createUser([
            'nis' => '3001',
            'nama' => 'Superadmin',
            'email' => 'superadmin@example.test',
            'level' => 3,
        ]);

        $response = $this->withSession($this->sessionFor($admin))->post(route('superadmin.users.store'), [
            'nama' => 'Calon Superadmin',
            'email' => 'calon-superadmin@example.test',
            'nis' => '9001',
            'level' => 3,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('superadmin.users', [
            'q' => '',
            'role' => 'semua',
            'room_type' => 'semua',
        ]));
        $response->assertSessionHasErrors('level');
        $this->assertDatabaseMissing('users', [
            'email' => 'calon-superadmin@example.test',
        ]);
    }

    public function test_regular_user_cannot_be_promoted_to_superadmin_from_user_form(): void
    {
        $admin = $this->createUser([
            'nis' => '3002',
            'nama' => 'Superadmin',
            'email' => 'superadmin-2@example.test',
            'level' => 3,
        ]);

        $target = $this->createUser([
            'nis' => '1001',
            'nama' => 'Ketua Kelas',
            'email' => 'ketua@example.test',
            'level' => 1,
        ]);

        $response = $this->withSession($this->sessionFor($admin))->post(route('superadmin.users.update', $target->id_user), [
            'nama' => $target->nama,
            'email' => $target->email,
            'nis' => $target->nis,
            'level' => 3,
            'password' => '',
        ]);

        $response->assertRedirect(route('superadmin.users', [
            'q' => '',
            'role' => 'semua',
            'room_type' => 'semua',
        ]));
        $response->assertSessionHasErrors('level');
        $this->assertDatabaseHas('users', [
            'id_user' => $target->id_user,
            'level' => 1,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'nis' => fake()->unique()->numerify('####'),
            'nama' => 'User Test',
            'email' => fake()->unique()->safeEmail(),
            'otp_enabled' => false,
            'password' => 'secret123',
            'level' => 1,
            'kelas' => null,
        ], $overrides));
    }

    /**
     * @return array<string, mixed>
     */
    private function sessionFor(User $user): array
    {
        return [
            'logged_in' => true,
            'user' => [
                'id_user' => $user->id_user,
                'nis' => $user->nis,
                'nama' => $user->nama,
                'email' => $user->email,
                'otp_enabled' => false,
                'level' => $user->level,
                'role_label' => 'Superadmin',
            ],
        ];
    }
}
