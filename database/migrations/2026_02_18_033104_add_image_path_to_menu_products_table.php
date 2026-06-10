<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ✅ infalible: solo agrega si NO existe
        if (!Schema::hasColumn('menu_products', 'image_path')) {
            Schema::table('menu_products', function (Blueprint $table) {
                $table->string('image_path')->nullable()->after('url');
            });
        }
    }

    public function down(): void
    {
        // ✅ infalible: solo elimina si existe
        if (Schema::hasColumn('menu_products', 'image_path')) {
            Schema::table('menu_products', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};