<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPermintaanSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permintaan = DB::table('permintaan')->pluck('id_permintaan', 'kode_permintaan');
        $barang = DB::table('barang')->pluck('id_barang', 'nama_barang');

        DB::table('detail_permintaan')->insert([
            [
                'id_permintaan' => $permintaan['PMT-20260414-001'],
                'id_barang' => $barang['meja siswa'],
                'jumlah_diminta' => 5,
                'jumlah_disetujui' => 5,
                'jumlah_diberikan' => 5,
                'keterangan' => 'Tambahan meja untuk siswa baru.',
            ],
            [
                'id_permintaan' => $permintaan['PMT-20260414-001'],
                'id_barang' => $barang['kursi siswa'],
                'jumlah_diminta' => 5,
                'jumlah_disetujui' => 5,
                'jumlah_diberikan' => 5,
                'keterangan' => 'Tambahan kursi untuk siswa baru.',
            ],
            [
                'id_permintaan' => $permintaan['PMT-20260414-002'],
                'id_barang' => $barang['proyektor'],
                'jumlah_diminta' => 1,
                'jumlah_disetujui' => 1,
                'jumlah_diberikan' => 0,
                'keterangan' => 'Menunggu persetujuan owner.',
            ],
            [
                'id_permintaan' => $permintaan['PMT-20260414-003'],
                'id_barang' => $barang['komputer'],
                'jumlah_diminta' => 10,
                'jumlah_disetujui' => 0,
                'jumlah_diberikan' => 0,
                'keterangan' => 'Permintaan ditolak wali kelas.',
            ],
        ]);
    }
}
