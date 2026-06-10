<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_visual_items', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_product_id')->nullable()->after('external_url');
            $table->index('menu_product_id');
        });
    }

    public function down(): void
    {
        Schema::table('material_visual_items', function (Blueprint $table) {
            $table->dropIndex(['menu_product_id']);
            $table->dropColumn('menu_product_id');
        });
    }
};