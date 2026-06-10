<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_card_images', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_card_images', 'path')) {
                $table->string('path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_card_images', function (Blueprint $table) {
            if (Schema::hasColumn('menu_card_images', 'path')) {
                $table->dropColumn('path');
            }
        });
    }
};