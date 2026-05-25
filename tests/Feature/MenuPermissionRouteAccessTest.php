<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MenuAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MenuPermissionRouteAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_level_three_can_open_user_menu_pages_as_superadmin(): void
    {
        app(MenuAccessService::class)->savePermissions([
            3 => ['kelas_saya_user', 'hak_akses', 'database_tools'],
        ]);

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

    public function test_level_three_sidebar_respects_saved_menu_permissions(): void
    {
        app(MenuAccessService::class)->savePermissions([
            3 => ['kelas_saya_user', 'hak_akses', 'database_tools'],
        ]);

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
        $response->assertSee('Kelas Saya');
        $response->assertSee('Hak Akses');
        $response->assertSee('Database');
        $response->assertDontSee('Pengajuan Kelas');
        $response->assertDontSee('Semua Ruangan');
        $response->assertDontSee('Data User');
    }

    public function test_level_three_can_open_every_get_menu_route(): void
    {
        app(MenuAccessService::class)->savePermissions([
            3 => app(MenuAccessService::class)->menuKeys(),
        ]);

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

    public function test_level_three_cannot_open_unchecked_menu_route(): void
    {
        app(MenuAccessService::class)->savePermissions([
            3 => ['hak_akses', 'database_tools'],
        ]);

        $user = User::create([
            'nis' => '3005',
            'nama' => 'Superadmin Limited Menu',
            'email' => 'superadmin-limited-menu@example.test',
            'otp_enabled' => false,
            'password' => 'secret123',
            'level' => 3,
            'kelas' => null,
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

        $this->withSession($session)
            ->get(route('class.inventory'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_level_three_sidebar_updates_after_hak_akses_form_is_saved(): void
    {
        $user = User::create([
            'nis' => '3006',
            'nama' => 'Superadmin Hak Akses Form',
            'email' => 'superadmin-hak-akses-form@example.test',
            'otp_enabled' => false,
            'password' => 'secret123',
            'level' => 3,
            'kelas' => null,
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

        $allMenus = app(MenuAccessService::class)->menuKeys();

        $this->withSession($session)
            ->post(route('hak_akses.update'), [
                'permissions' => [
                    3 => $allMenus,
                ],
            ])
            ->assertRedirect(route('hak_akses.index'));

        $this->withSession($session)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Kelas Saya')
            ->assertSee('Pengajuan Kelas')
            ->assertSee('Semua Ruangan')
            ->assertSee('Data User');

        $this->withSession($session)
            ->post(route('hak_akses.update'), [
                'permissions' => [
                    3 => ['hak_akses', 'database_tools'],
                ],
            ])
            ->assertRedirect(route('hak_akses.index'));

        $this->withSession($session)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Hak Akses')
            ->assertSee('Database')
            ->assertDontSee('Kelas Saya')
            ->assertDontSee('Pengajuan Kelas')
            ->assertDontSee('Semua Ruangan')
            ->assertDontSee('Data User');

        $this->withSession($session)
            ->get(route('class.inventory'))
            ->assertRedirect(route('dashboard'));
    }
}
