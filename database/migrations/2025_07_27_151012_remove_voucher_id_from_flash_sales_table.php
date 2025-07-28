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
         Schema::table('flash_sales', function (Blueprint $table) {
            // Xóa khóa ngoại trước
            $table->dropForeign(['voucher_id']);

            // Sau đó xóa cột
            $table->dropColumn('voucher_id');
            $table->integer('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_sales', function (Blueprint $table) {
            //
        });
    }
};
