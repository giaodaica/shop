<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Bỏ ràng buộc cũ
            $table->dropForeign(['voucher_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_books_id']);

            // Tạo lại foreign key với set null
            $table->foreign('voucher_id')
                ->references('id')
                ->on('vouchers')
                ->onDelete('set null');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('address_books_id')
                ->references('id')
                ->on('address_books')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
 
    }
};


