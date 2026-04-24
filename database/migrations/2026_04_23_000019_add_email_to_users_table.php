<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $emailColumn = $table->string('email')->nullable();

                if (Schema::hasColumn('users', 'username')) {
                    $emailColumn->after('username');
                    return;
                }

                $emailColumn->after('nama');
            });
        }

        DB::table('users')
            ->where('email', '')
            ->update(['email' => null]);

        $hasUniqueIndex = collect(DB::select("
            SELECT index_name
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'users'
              AND index_name = 'users_email_unique'
        "))->isNotEmpty();

        if (! $hasUniqueIndex) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'email')) {
            return;
        }

        $hasUniqueIndex = collect(DB::select("
            SELECT index_name
            FROM information_schema.statistics
            WHERE table_schema = DATABASE()
              AND table_name = 'users'
              AND index_name = 'users_email_unique'
        "))->isNotEmpty();

        Schema::table('users', function (Blueprint $table) use ($hasUniqueIndex) {
            if ($hasUniqueIndex) {
                $table->dropUnique('users_email_unique');
            }

            $table->dropColumn('email');
        });
    }
};
