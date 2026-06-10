<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menu_nodes')->nullOnDelete();

            $table->string('label', 255);
            $table->string('slug', 255);
            $table->string('key', 512)->unique();

            $table->string('url', 2048)->nullable();

            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path', 255)->nullable();

            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['parent_id', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_nodes');
    }
};