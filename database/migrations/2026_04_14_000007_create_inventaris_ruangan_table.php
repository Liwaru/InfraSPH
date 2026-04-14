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
        Schema::create('inventaris_ruangan', function (Blueprint $table) {
            $table->id('id_inventaris_ruangan');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('jumlah_baik')->default(0);
            $table->unsignedBigInteger('jumlah_rusak')->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_user_pengubah')->nullable();

            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->cascadeOnDelete();
            $table->foreign('id_barang')->references('id_barang')->on('barang')->cascadeOnDelete();
            $table->foreign('id_user_pengubah')->references('id_user')->on('users')->nullOnDelete();

            $table->unique(['id_ruangan', 'id_barang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_ruangan');
    }
};
