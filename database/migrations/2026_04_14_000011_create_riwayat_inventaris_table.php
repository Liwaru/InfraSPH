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
        Schema::create('riwayat_inventaris', function (Blueprint $table) {
            $table->id('id_riwayat_inventaris');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_permintaan')->nullable();
            $table->unsignedBigInteger('id_user_petugas');
            $table->string('jenis_riwayat');
            $table->unsignedBigInteger('jumlah');
            $table->dateTime('tanggal_riwayat');
            $table->text('keterangan')->nullable();

            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->cascadeOnDelete();
            $table->foreign('id_barang')->references('id_barang')->on('barang')->cascadeOnDelete();
            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan')->nullOnDelete();
            $table->foreign('id_user_petugas')->references('id_user')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_inventaris');
    }
};
