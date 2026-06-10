<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_products', function (Blueprint $table) {
            $table->id();

            // A qué “pantalla” del menú pertenece: ej "productos/escritorios"
            $table->string('menu_key', 255)->index();

            $table->string('title', 255);
            $table->text('description')->nullable();

            // Imagen guardada en storage/app/public/menu_products/...
            $table->string('image_path', 255)->nullable();

            // Link opcional (pdf, drive, etc.)
            $table->string('url', 2048)->nullable();

            // Para ordenar
            $table->unsignedInteger('sort')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_products');
    }
};