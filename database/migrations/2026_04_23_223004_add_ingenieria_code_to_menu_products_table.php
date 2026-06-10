<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('menu_products', 'ingenieria_code')) {
            Schema::table('menu_products', function (Blueprint $table) {
                $table->string('ingenieria_code', 100)->nullable()->after('menu_key');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('menu_products', 'ingenieria_code')) {
            Schema::table('menu_products', function (Blueprint $table) {
                $table->dropColumn('ingenieria_code');
            });
        }
    }
};