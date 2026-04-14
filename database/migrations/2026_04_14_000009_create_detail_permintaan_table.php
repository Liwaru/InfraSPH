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
        Schema::create('detail_permintaan', function (Blueprint $table) {
            $table->id('id_detail_permintaan');
            $table->unsignedBigInteger('id_permintaan');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('jumlah_diminta');
            $table->unsignedBigInteger('jumlah_disetujui')->default(0);
            $table->unsignedBigInteger('jumlah_diberikan')->default(0);
            $table->text('keterangan')->nullable();

            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan')->cascadeOnDelete();
            $table->foreign('id_barang')->references('id_barang')->on('barang')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_permintaan');
    }
};
