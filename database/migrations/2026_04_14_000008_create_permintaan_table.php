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
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id('id_permintaan');
            $table->string('kode_permintaan')->unique();
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_user_peminta');
            $table->string('jenis_permintaan');
            $table->string('status_permintaan')->default('diajukan');
            $table->text('catatan_peminta')->nullable();
            $table->date('tanggal_permintaan');

            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan')->cascadeOnDelete();
            $table->foreign('id_user_peminta')->references('id_user')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan');
    }
};
