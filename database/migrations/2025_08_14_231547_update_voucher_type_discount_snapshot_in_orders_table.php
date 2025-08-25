<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Cách này dùng ALTER TABLE trực tiếp vì Laravel không hỗ trợ đổi enum bằng Blueprint
        DB::statement("ALTER TABLE orders MODIFY voucher_type_discount_snapshot ENUM('percent', 'value') NULL");
    }

    public function down(): void
    {
       
    }
};
