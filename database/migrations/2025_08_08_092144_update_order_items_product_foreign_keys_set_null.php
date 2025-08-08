<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Bỏ ràng buộc cũ
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_variant_id']);

            // Cho phép null để dùng set null
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->unsignedBigInteger('product_variant_id')->nullable()->change();

            // Thêm lại foreign key với set null
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('set null');

            $table->foreign('product_variant_id')
                ->references('id')->on('product_variants')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
      
    }
};

