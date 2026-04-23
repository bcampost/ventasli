<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menu_product_material_color')) {
            Schema::create('menu_product_material_color', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_product_id');
                $table->unsignedBigInteger('material_color_id');
                $table->timestamps();

                $table->unique(['menu_product_id', 'material_color_id'], 'mpmc_product_color_unique');
                $table->index('menu_product_id');
                $table->index('material_color_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_product_material_color');
    }
};