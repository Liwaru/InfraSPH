<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_change_archives', function (Blueprint $table) {
            $table->id();
            $table->string('table_name', 120);
            $table->string('primary_key', 120);
            $table->string('record_id', 120);
            $table->string('action', 80);
            $table->string('module', 120);
            $table->string('target', 255)->nullable();
            $table->json('snapshot');
            $table->json('related_snapshot')->nullable();
            $table->timestamp('restored_at')->nullable();
            $table->unsignedBigInteger('restored_by')->nullable();
            $table->timestamps();

            $table->index(['table_name', 'record_id']);
            $table->index(['action', 'module']);
            $table->index('restored_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_change_archives');
    }
};
