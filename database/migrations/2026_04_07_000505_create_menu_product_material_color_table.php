<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_product_material_color', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_product_id')->constrained('menu_products')->cascadeOnDelete();
            $table->foreignId('material_color_id')->constrained('material_colors')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(
                ['menu_product_id', 'material_color_id'],
                'menu_product_material_color_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_product_material_color');
    }
};