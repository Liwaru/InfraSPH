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
        Schema::create('persetujuan_permintaan', function (Blueprint $table) {
            $table->id('id_persetujuan_permintaan');
            $table->unsignedBigInteger('id_permintaan');
            $table->unsignedBigInteger('id_user_penyetuju');
            $table->string('tahap_persetujuan');
            $table->string('status_persetujuan');
            $table->text('catatan_persetujuan')->nullable();
            $table->dateTime('tanggal_persetujuan')->nullable();

            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan')->cascadeOnDelete();
            $table->foreign('id_user_penyetuju')->references('id_user')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_permintaan');
    }
};
