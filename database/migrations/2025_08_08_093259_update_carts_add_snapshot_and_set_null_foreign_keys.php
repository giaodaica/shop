<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Nếu có foreign key cũ thì drop (nếu không có sẽ báo lỗi -> ensure tên đúng)
            $table->dropForeign(['product_variants_id']);
            $table->dropForeign(['flash_sale_items_id']);

            // Cho phép null cho các cột liên quan
            $table->unsignedBigInteger('product_variants_id')->nullable()->change();
            $table->unsignedBigInteger('flash_sale_items_id')->nullable()->change();

            // Thêm cột snapshot để hiển thị khi sản phẩm/variant bị xóa
            $table->string('product_name')->nullable()->after('flash_sale_items_id');
            $table->string('product_image_url')->nullable()->after('product_name');

            // Tạo lại foreign key với null on delete
            $table->foreign('product_variants_id')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();

            $table->foreign('flash_sale_items_id')
                ->references('id')
                ->on('flash_sale_items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {

    }
};
