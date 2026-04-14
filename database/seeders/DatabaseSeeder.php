<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nis' => null,
                'nama' => 'owner',
                'password' => Hash::make('owner123'),
                'level' => 4,
            ],
            [
                'nis' => null,
                'nama' => 'superadmin',
                'password' => Hash::make('superadmin123'),
                'level' => 3,
            ],
            [
                'nis' => null,
                'nama' => 'admin',
                'password' => Hash::make('admin123'),
                'level' => 2,
            ],
            [
                'nis' => '24161033',
                'nama' => 'Hendrik',
                'password' => Hash::make('hendrik123'),
                'level' => 1,
            ],
        ]);

        $this->call([
            RuanganSeeder::class,
            KategoriBarangSeeder::class,
            BarangSeeder::class,
            PenugasanRuanganSeeder::class,
            InventarisRuanganSeeder::class,
            PermintaanSeeder::class,
            DetailPermintaanSeeder::class,
            PersetujuanPermintaanSeeder::class,
            RiwayatInventarisSeeder::class,
        ]);
    }
}
