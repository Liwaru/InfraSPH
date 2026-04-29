<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_permintaan', function (Blueprint $table) {
            $table->string('foto_kerusakan')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('detail_permintaan', function (Blueprint $table) {
            $table->dropColumn('foto_kerusakan');
        });
    }
};
