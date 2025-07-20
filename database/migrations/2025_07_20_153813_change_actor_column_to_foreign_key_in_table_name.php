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
       Schema::table('vouchers_logs', function (Blueprint $table) {
            // Xoá cột cũ
            $table->dropColumn('actor');
        });

        Schema::table('vouchers_logs', function (Blueprint $table) {
            // Thêm lại cột actor kiểu bigInteger để làm foreign key
            $table->unsignedBigInteger('actor')->nullable()->after('order_id');

            // Thêm khoá ngoại
            $table->foreign('actor')
                ->references('id')
                ->on('users') // bảng bạn muốn liên kết tới
                ->onDelete('cascade'); // hoặc cascade, restrict, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers_logs', function (Blueprint $table) {
            //
        });
    }
};
