<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_locks', function (Blueprint $table) {
            if (Schema::hasColumn('user_locks', 'reason')) {
                $table->dropColumn('reason');
            }

            // Thêm cột mới nếu chưa có
            if (!Schema::hasColumn('user_locks', 'lock_reason_id')) {
                $table->foreignId('lock_reason_id')
                      ->after('user_id')
                      ->nullable()
                      ->constrained('lock_reasons')
                      ->onDelete('restrict');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_locks', function (Blueprint $table) {
            $table->dropForeign(['lock_reason_id']);
            $table->dropColumn('lock_reason_id');

            $table->string('reason')->nullable();
        });
    }
};