<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SuperadminRoomDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_delete_room_with_related_data(): void
    {
        $admin = $this->createUser([
            'nis' => '3005',
            'nama' => 'Superadmin Room',
            'email' => 'superadmin-room@example.test',
            'level' => 3,
        ]);

        $student = $this->createUser([
            'nis' => '1005',
            'nama' => 'Ketua Ruangan',
            'email' => 'ketua-ruangan@example.test',
            'level' => 1,
        ]);

        $roomId = DB::table('ruangan')->insertGetId([
            'kode_ruangan' => 'DEL-01',
            'nama_ruangan' => 'Ruang Hapus',
            'jenis_ruangan' => 'kelas',
            'unit' => 'SMP',
            'lokasi' => 'Lantai 1',
            'keterangan' => null,
            'status' => 'aktif',
        ]);

        $categoryId = DB::table('kategori_barang')->insertGetId([
            'nama_kategori' => 'Tes Hapus',
            'keterangan' => null,
            'status' => 'aktif',
        ]);

        $itemId = DB::table('barang')->insertGetId([
            'id_kategori_barang' => $categoryId,
            'nama_barang' => 'Kursi Hapus',
            'satuan' => 'unit',
            'keterangan' => null,
            'status' => 'aktif',
        ]);

        DB::table('inventaris_ruangan')->insert([
            'id_ruangan' => $roomId,
            'id_barang' => $itemId,
            'jumlah_baik' => 2,
            'jumlah_rusak' => 1,
            'keterangan' => null,
            'id_user_pengubah' => $admin->id_user,
        ]);

        DB::table('penugasan_ruangan')->insert([
            'id_user' => $student->id_user,
            'id_ruangan' => $roomId,
            'peran_ruangan' => 'ketua_kelas',
            'status' => 'aktif',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => null,
        ]);

        $requestId = DB::table('permintaan')->insertGetId([
            'kode_permintaan' => 'REQ-DELETE-01',
            'id_ruangan' => $roomId,
            'id_user_peminta' => $student->id_user,
            'jenis_permintaan' => 'barang_baru',
            'status_permintaan' => 'diajukan',
            'catatan_peminta' => null,
            'tanggal_permintaan' => now()->toDateString(),
        ]);

        DB::table('detail_permintaan')->insert([
            'id_permintaan' => $requestId,
            'id_barang' => $itemId,
            'jumlah_diminta' => 1,
            'jumlah_disetujui' => 0,
            'jumlah_diberikan' => 0,
            'keterangan' => null,
        ]);

        DB::table('persetujuan_permintaan')->insert([
            'id_permintaan' => $requestId,
            'id_user_penyetuju' => $admin->id_user,
            'tahap_persetujuan' => 'superadmin',
            'status_persetujuan' => 'pending',
            'catatan_persetujuan' => null,
            'tanggal_persetujuan' => null,
        ]);

        DB::table('riwayat_inventaris')->insert([
            'id_ruangan' => $roomId,
            'id_barang' => $itemId,
            'id_permintaan' => $requestId,
            'id_user_petugas' => $admin->id_user,
            'jenis_riwayat' => 'masuk',
            'jumlah' => 1,
            'tanggal_riwayat' => now(),
            'keterangan' => null,
        ]);

        $response = $this->withSession($this->sessionFor($admin))
            ->post(route('superadmin.rooms.delete', $roomId));

        $response->assertRedirect(route('superadmin.rooms', [
            'q' => '',
            'type' => 'semua',
            'unit' => 'semua',
        ]));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('ruangan', ['id_ruangan' => $roomId]);
        $this->assertDatabaseMissing('inventaris_ruangan', ['id_ruangan' => $roomId]);
        $this->assertDatabaseMissing('penugasan_ruangan', ['id_ruangan' => $roomId]);
        $this->assertDatabaseMissing('permintaan', ['id_permintaan' => $requestId]);
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
