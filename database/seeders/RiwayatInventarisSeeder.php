<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatInventarisSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadmin = DB::table('users')->where('nama', 'superadmin')->value('id_user');
        $ruangan7A = DB::table('ruangan')->where('kode_ruangan', 'KLS-7A')->value('id_ruangan');
        $permintaan = DB::table('permintaan')->where('kode_permintaan', 'PMT-20260414-001')->value('id_permintaan');
        $barang = DB::table('barang')->pluck('id_barang', 'nama_barang');

        DB::table('riwayat_inventaris')->insert([
            [
                'id_ruangan' => $ruangan7A,
                'id_barang' => $barang['meja siswa'],
                'id_permintaan' => $permintaan,
                'id_user_petugas' => $superadmin,
                'jenis_riwayat' => 'tambah',
                'jumlah' => 5,
                'tanggal_riwayat' => '2026-04-14 13:00:00',
                'keterangan' => 'Penambahan meja siswa untuk kelas 7A.',
            ],
            [
                'id_ruangan' => $ruangan7A,
                'id_barang' => $barang['kursi siswa'],
                'id_permintaan' => $permintaan,
                'id_user_petugas' => $superadmin,
                'jenis_riwayat' => 'tambah',
                'jumlah' => 5,
                'tanggal_riwayat' => '2026-04-14 13:05:00',
                'keterangan' => 'Penambahan kursi siswa untuk kelas 7A.',
            ],
        ]);
    }
}
