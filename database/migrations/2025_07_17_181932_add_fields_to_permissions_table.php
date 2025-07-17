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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
            $table->string('description')->nullable()->after('title');
            $table->string('url')->nullable()->after('description');
            $table->string('action')->nullable()->after('url');
            $table->unsignedBigInteger('parent_id')->nullable()->after('action');
            $table->integer('order')->default(0)->after('parent_id');
            $table->softDeletes()->after('order'); // Adds deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'url',
                'action',
                'parent_id',
                'order',
                'deleted_at',
            ]);
        });
    }
};
