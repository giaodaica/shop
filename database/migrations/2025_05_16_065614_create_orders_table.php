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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_books_id')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('final_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'success', 'cancelled'])->default('pending');
            $table->string('code_order', 16)->unique();
            $table->enum('pay_method', ['COD', 'QR'])->nullable();
            $table->enum('status_pay', ['unpaid', 'paid', 'failed', 'cancelled', 'cod_paid'])->default('unpaid');
            $table->timestamps();
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_books_id')->references('id')->on('address_books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
