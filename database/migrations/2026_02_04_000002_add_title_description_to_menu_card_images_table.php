<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_card_images', function (Blueprint $table) {

            // Si no tienes 'path' en esta tabla, NO usamos after()
            // Solo agregamos columnas de forma segura.

            if (!Schema::hasColumn('menu_card_images', 'title')) {
                $table->string('title')->nullable();
            }

            if (!Schema::hasColumn('menu_card_images', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_card_images', function (Blueprint $table) {

            if (Schema::hasColumn('menu_card_images', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('menu_card_images', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};