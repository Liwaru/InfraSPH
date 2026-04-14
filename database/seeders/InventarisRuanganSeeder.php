<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventarisRuanganSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadmin = DB::table('users')->where('nama', 'superadmin')->value('id_user');

        $ruangan = DB::table('ruangan')->pluck('id_ruangan', 'kode_ruangan');
        $barang = DB::table('barang')->pluck('id_barang', 'nama_barang');

        DB::table('inventaris_ruangan')->insert([
            [
                'id_ruangan' => $ruangan['KLS-7A'],
                'id_barang' => $barang['meja siswa'],
                'jumlah_baik' => 30,
                'jumlah_rusak' => 1,
                'keterangan' => 'Inventaris awal kelas 7A',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7A'],
                'id_barang' => $barang['kursi siswa'],
                'jumlah_baik' => 30,
                'jumlah_rusak' => 2,
                'keterangan' => 'Inventaris awal kelas 7A',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7A'],
                'id_barang' => $barang['meja guru'],
                'jumlah_baik' => 1,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kelas 7A',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7A'],
                'id_barang' => $barang['kursi guru'],
                'jumlah_baik' => 1,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kelas 7A',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7A'],
                'id_barang' => $barang['lemari'],
                'jumlah_baik' => 1,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kelas 7A',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7A'],
                'id_barang' => $barang['papan tulis'],
                'jumlah_baik' => 1,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kelas 7A',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7B'],
                'id_barang' => $barang['meja siswa'],
                'jumlah_baik' => 32,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kelas 7B',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KLS-7B'],
                'id_barang' => $barang['kursi siswa'],
                'jumlah_baik' => 32,
                'jumlah_rusak' => 1,
                'keterangan' => 'Inventaris awal kelas 7B',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['LAB-SMP'],
                'id_barang' => $barang['komputer'],
                'jumlah_baik' => 20,
                'jumlah_rusak' => 2,
                'keterangan' => 'Inventaris awal lab komputer SMP',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['LAB-SMP'],
                'id_barang' => $barang['printer'],
                'jumlah_baik' => 1,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal lab komputer SMP',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['LAB-SMK'],
                'id_barang' => $barang['komputer'],
                'jumlah_baik' => 24,
                'jumlah_rusak' => 1,
                'keterangan' => 'Inventaris awal lab komputer SMK',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KTR-KPS'],
                'id_barang' => $barang['ac'],
                'jumlah_baik' => 1,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kantor kepala sekolah',
                'id_user_pengubah' => $superadmin,
            ],
            [
                'id_ruangan' => $ruangan['KTR-GRU-2'],
                'id_barang' => $barang['kipas angin'],
                'jumlah_baik' => 2,
                'jumlah_rusak' => 0,
                'keterangan' => 'Inventaris awal kantor guru lantai 2',
                'id_user_pengubah' => $superadmin,
            ],
        ]);
    }
}
