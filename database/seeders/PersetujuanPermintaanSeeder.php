<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersetujuanPermintaanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permintaan = DB::table('permintaan')->pluck('id_permintaan', 'kode_permintaan');
        $admin = DB::table('users')->where('nama', 'admin')->value('id_user');
        $owner = DB::table('users')->where('nama', 'owner')->value('id_user');

        DB::table('persetujuan_permintaan')->insert([
            [
                'id_permintaan' => $permintaan['PMT-20260414-001'],
                'id_user_penyetuju' => $admin,
                'tahap_persetujuan' => 'admin',
                'status_persetujuan' => 'disetujui',
                'catatan_persetujuan' => 'Disetujui wali kelas.',
                'tanggal_persetujuan' => '2026-04-14 08:00:00',
            ],
            [
                'id_permintaan' => $permintaan['PMT-20260414-001'],
                'id_user_penyetuju' => $owner,
                'tahap_persetujuan' => 'owner',
                'status_persetujuan' => 'disetujui',
                'catatan_persetujuan' => 'Disetujui kepala sekolah.',
                'tanggal_persetujuan' => '2026-04-14 09:00:00',
            ],
            [
                'id_permintaan' => $permintaan['PMT-20260414-002'],
                'id_user_penyetuju' => $admin,
                'tahap_persetujuan' => 'admin',
                'status_persetujuan' => 'disetujui',
                'catatan_persetujuan' => 'Menunggu persetujuan owner.',
                'tanggal_persetujuan' => '2026-04-14 10:00:00',
            ],
            [
                'id_permintaan' => $permintaan['PMT-20260414-003'],
                'id_user_penyetuju' => $admin,
                'tahap_persetujuan' => 'admin',
                'status_persetujuan' => 'ditolak',
                'catatan_persetujuan' => 'Jumlah permintaan belum sesuai kebutuhan kelas.',
                'tanggal_persetujuan' => '2026-04-14 11:00:00',
            ],
        ]);
    }
}
