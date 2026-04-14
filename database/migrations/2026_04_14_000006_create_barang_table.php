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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang');
            $table->unsignedBigInteger('id_kategori_barang');
            $table->string('nama_barang');
            $table->string('satuan')->default('unit');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('aktif');

            $table->foreign('id_kategori_barang')
                ->references('id_kategori_barang')
                ->on('kategori_barang')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
