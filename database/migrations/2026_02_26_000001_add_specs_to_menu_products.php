<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_products', function (Blueprint $table) {
            $table->json('gallery_images')->nullable()->after('image_path'); // slider
            $table->json('specs')->nullable()->after('gallery_images');      // ficha (medidas/colores/etc)
        });
    }

    public function down(): void
    {
        Schema::table('menu_products', function (Blueprint $table) {
            $table->dropColumn(['gallery_images', 'specs']);
        });
    }
};