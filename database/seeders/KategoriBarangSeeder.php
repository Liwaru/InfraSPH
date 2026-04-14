<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('kategori_barang')->insert([
            [
                'nama_kategori' => 'furnitur',
                'keterangan' => 'Perabot ruangan sekolah',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'elektronik',
                'keterangan' => 'Perangkat elektronik ruangan',
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'alat pembelajaran',
                'keterangan' => 'Peralatan pendukung belajar mengajar',
                'status' => 'aktif',
            ],
        ]);
    }
}
