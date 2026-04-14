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
        Schema::create('penugasan_ruangan', function (Blueprint $table) {
            $table->id('id_penugasan_ruangan');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_ruangan');
            $table->string('peran_ruangan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('aktif');

            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_ruangan');
    }
};
