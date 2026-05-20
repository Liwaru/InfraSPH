<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterQuerySafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_inventory_page_accepts_array_shaped_filter_query(): void
    {
        $user = User::create([
            'nis' => '3002',
            'nama' => 'Superadmin Filter',
            'email' => 'superadmin-filter@example.test',
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
        ])->get(route('superadmin.items', [
            'q' => ['kursi'],
            'category' => ['semua'],
            'room' => ['semua'],
            'room_type' => ['semua'],
            'condition' => ['semua'],
        ]));

        $response->assertOk();
        $response->assertSee('Data Inventaris');
    }
}
