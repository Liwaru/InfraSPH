<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel cache bawaan Laravel dinonaktifkan agar skema fokus pada 10 tabel utama aplikasi.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada tabel yang dihapus karena migration ini tidak membuat tabel.
    }
};
