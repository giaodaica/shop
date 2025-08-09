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
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id');
            $table->unsignedBigInteger('product_variant_id')->nullable()->after('order_id');
    
            // Nếu ông muốn ràng buộc FK thì mở comment mấy dòng này:
            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            // $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Nếu có FK thì drop trước:
            // $table->dropForeign(['order_id']);
            // $table->dropForeign(['product_variant_id']);
            $table->dropColumn(['order_id', 'product_variant_id']);
        });
    }
};
