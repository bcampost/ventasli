<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('material_colors')) {
            Schema::create('material_colors', function (Blueprint $table) {
                $table->id();
                $table->string('type', 20);
                $table->string('name', 100);
                $table->unsignedInteger('sort')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['type', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('material_colors');
    }
};