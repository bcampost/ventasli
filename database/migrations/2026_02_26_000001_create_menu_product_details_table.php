<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_product_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_product_id')
                ->constrained('menu_products')
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // slider
            $table->json('images')->nullable(); // array de paths

            // medidas
            $table->string('largo')->nullable();
            $table->string('ancho')->nullable();
            $table->string('alto')->nullable();

            // colores (pills)
            $table->json('acero_colors')->nullable();     // ["Negro", "Blanco", ...]
            $table->json('melamina_colors')->nullable();  // ["Encino", "Nogal", ...]

            $table->timestamps();

            $table->unique('menu_product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_product_details');
    }
};