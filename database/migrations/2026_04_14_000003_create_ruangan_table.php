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
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->string('jenis_ruangan');
            $table->string('unit');
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
