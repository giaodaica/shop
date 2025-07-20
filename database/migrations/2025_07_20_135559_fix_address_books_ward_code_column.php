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
        Schema::table('address_books', function (Blueprint $table) {
            // Kiểm tra nếu column wards_code tồn tại thì đổi tên thành ward_code
            if (Schema::hasColumn('address_books', 'wards_code')) {
                $table->renameColumn('wards_code', 'ward_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('address_books', function (Blueprint $table) {
            // Đổi tên lại từ ward_code thành wards_code
            if (Schema::hasColumn('address_books', 'ward_code')) {
                $table->renameColumn('ward_code', 'wards_code');
            }
        });
    }
};
