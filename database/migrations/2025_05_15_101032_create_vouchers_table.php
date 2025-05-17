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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type_discount',['percent','value']);
            $table->decimal('value',10,2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('used')->default(0);
            $table->integer('max_used')->nullable();
            $table->decimal('min_order_value',10,2)->nullable();
            $table->enum('status',['active', 'expired', 'disabled', 'used_up'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
