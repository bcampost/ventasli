<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_product_details', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_product_details', 'steel_colors')) {
                $table->json('steel_colors')->nullable();
            }
            if (!Schema::hasColumn('menu_product_details', 'melamine_colors')) {
                $table->json('melamine_colors')->nullable();
            }
            if (!Schema::hasColumn('menu_product_details', 'variant_images')) {
                $table->json('variant_images')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_product_details', function (Blueprint $table) {
            if (Schema::hasColumn('menu_product_details', 'steel_colors')) {
                $table->dropColumn('steel_colors');
            }
            if (Schema::hasColumn('menu_product_details', 'melamine_colors')) {
                $table->dropColumn('melamine_colors');
            }
            if (Schema::hasColumn('menu_product_details', 'variant_images')) {
                $table->dropColumn('variant_images');
            }
        });
    }
};