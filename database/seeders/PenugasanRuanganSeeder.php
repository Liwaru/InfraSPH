<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenugasanRuanganSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $ruangan7A = DB::table('ruangan')->where('kode_ruangan', 'KLS-7A')->value('id_ruangan');
        $admin = DB::table('users')->where('nama', 'admin')->value('id_user');
        $hendrik = DB::table('users')->where('nama', 'Hendrik')->value('id_user');

        DB::table('penugasan_ruangan')->insert([
            [
                'id_user' => $admin,
                'id_ruangan' => $ruangan7A,
                'peran_ruangan' => 'wali_kelas',
                'tanggal_mulai' => '2026-04-14',
                'tanggal_selesai' => null,
                'status' => 'aktif',
            ],
            [
                'id_user' => $hendrik,
                'id_ruangan' => $ruangan7A,
                'peran_ruangan' => 'ketua_kelas',
                'tanggal_mulai' => '2026-04-14',
                'tanggal_selesai' => null,
                'status' => 'aktif',
            ],
        ]);
    }
}
