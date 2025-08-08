<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('voucher_code_snapshot')->nullable()->after('voucher_id');
            $table->enum('voucher_type_discount_snapshot', ['percent', 'fixed'])->nullable()->after('voucher_code_snapshot');
            $table->decimal('voucher_value_snapshot', 10, 2)->nullable()->after('voucher_type_discount_snapshot');
            $table->decimal('voucher_max_discount_snapshot', 10, 2)->nullable()->after('voucher_value_snapshot');
            $table->decimal('voucher_min_order_value_snapshot', 10, 2)->nullable()->after('voucher_max_discount_snapshot');
            $table->dateTime('voucher_start_date_snapshot')->nullable()->after('voucher_min_order_value_snapshot');
            $table->dateTime('voucher_end_date_snapshot')->nullable()->after('voucher_start_date_snapshot');
        });
    }

    public function down(): void
    {
       
    }
};

