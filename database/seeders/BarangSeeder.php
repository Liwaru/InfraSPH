<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $kategori = DB::table('kategori_barang')
            ->pluck('id_kategori_barang', 'nama_kategori');

        DB::table('barang')->insert([
            [
                'id_kategori_barang' => $kategori['furnitur'],
                'nama_barang' => 'meja siswa',
                'satuan' => 'buah',
                'keterangan' => 'Meja untuk siswa',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['furnitur'],
                'nama_barang' => 'kursi siswa',
                'satuan' => 'buah',
                'keterangan' => 'Kursi untuk siswa',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['furnitur'],
                'nama_barang' => 'meja guru',
                'satuan' => 'buah',
                'keterangan' => 'Meja untuk guru',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['furnitur'],
                'nama_barang' => 'kursi guru',
                'satuan' => 'buah',
                'keterangan' => 'Kursi untuk guru',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['furnitur'],
                'nama_barang' => 'lemari',
                'satuan' => 'buah',
                'keterangan' => 'Lemari penyimpanan',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['elektronik'],
                'nama_barang' => 'ac',
                'satuan' => 'unit',
                'keterangan' => 'Pendingin ruangan',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['elektronik'],
                'nama_barang' => 'kipas angin',
                'satuan' => 'unit',
                'keterangan' => 'Kipas angin ruangan',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['elektronik'],
                'nama_barang' => 'komputer',
                'satuan' => 'unit',
                'keterangan' => 'Komputer ruangan',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['elektronik'],
                'nama_barang' => 'printer',
                'satuan' => 'unit',
                'keterangan' => 'Printer ruangan',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['alat pembelajaran'],
                'nama_barang' => 'papan tulis',
                'satuan' => 'buah',
                'keterangan' => 'Papan tulis kelas',
                'status' => 'aktif',
            ],
            [
                'id_kategori_barang' => $kategori['alat pembelajaran'],
                'nama_barang' => 'proyektor',
                'satuan' => 'unit',
                'keterangan' => 'Alat presentasi kelas',
                'status' => 'aktif',
            ],
        ]);
    }
}
