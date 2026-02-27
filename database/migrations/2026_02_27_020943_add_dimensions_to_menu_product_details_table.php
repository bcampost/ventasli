<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_product_details', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_product_details', 'length')) {
                $table->string('length', 50)->nullable()->after('description');
            }
            if (!Schema::hasColumn('menu_product_details', 'width')) {
                $table->string('width', 50)->nullable()->after('length');
            }
            if (!Schema::hasColumn('menu_product_details', 'height')) {
                $table->string('height', 50)->nullable()->after('width');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_product_details', function (Blueprint $table) {
            if (Schema::hasColumn('menu_product_details', 'length')) $table->dropColumn('length');
            if (Schema::hasColumn('menu_product_details', 'width')) $table->dropColumn('width');
            if (Schema::hasColumn('menu_product_details', 'height')) $table->dropColumn('height');
        });
    }
};