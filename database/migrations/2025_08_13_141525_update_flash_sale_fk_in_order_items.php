<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Bỏ ràng buộc cũ
            $table->dropForeign(['flash_sale_items_id']);

            // Thêm lại foreign key với ON DELETE SET NULL
            $table->foreign('flash_sale_items_id')
                ->references('id')
                ->on('flash_sale_items')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
      
    }
};
