<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MenuPermissionRouteAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_three_can_open_user_menu_pages_as_superadmin(): void
    {
        $user = User::create([
            'nis' => '3002',
            'nama' => 'Superadmin Level 3',
            'email' => 'superadmin-level3@example.test',
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
        ])->get(route('class.inventory'));

        $response->assertOk();
        $response->assertSee('Kelas Saya');
    }

    public function test_level_three_sidebar_gets_all_menus_by_default(): void
    {
        $user = User::create([
            'nis' => '3003',
            'nama' => 'Superadmin Menu Level 3',
            'email' => 'superadmin-menu-level3@example.test',
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
        $response->assertSee('Data User');
        $response->assertSee('Data Ruangan');
        $response->assertSee('Data Inventaris');
        $response->assertSee('Kelas Saya');
        $response->assertSee('Pengajuan Kelas');
        $response->assertSee('Semua Ruangan');
        $response->assertSee('Hak Akses');
    }

    public function test_level_three_can_open_every_get_menu_route(): void
    {
        $user = User::create([
            'nis' => '3004',
            'nama' => 'Superadmin All Menu',
            'email' => 'superadmin-all-menu@example.test',
            'otp_enabled' => false,
            'password' => 'secret123',
            'level' => 3,
            'kelas' => null,
        ]);

        DB::table('ruangan')->insert([
            'kode_ruangan' => 'TEST-01',
            'nama_ruangan' => 'Ruang Test',
            'jenis_ruangan' => 'kelas',
            'unit' => 'SMK',
            'lokasi' => 'Lantai 1',
            'keterangan' => null,
            'status' => 'aktif',
        ]);

        $session = [
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
        ];

        $routes = [
            'class.inventory',
            'requests.create',
            'requests.history',
            'admin.class.inventory',
            'admin.requests.inbox',
            'admin.requests.history',
            'superadmin.users',
            'superadmin.rooms',
            'superadmin.items',
            'superadmin.requests.realization',
            'superadmin.reports',
            'hak_akses.index',
            'superadmin.database',
            'owner.rooms',
            'owner.inventories',
            'owner.requests.approval',
            'owner.reports',
            'activity.logs',
        ];

        foreach ($routes as $routeName) {
            $response = $this->withSession($session)->get(route($routeName));

            $response->assertOk("Route {$routeName} should be accessible for level 3 superadmin.");
        }
    }
}
