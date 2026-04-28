<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_visual_items', function (Blueprint $table) {
            $table->id();

            $table->string('section', 50); 
            // catalogos, renders, fotos, videos, proyectos

            $table->string('parent_key')->nullable();
            // para agrupar: renders/escritorios, fotos/industrial, etc.

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('type', 30)->default('file');
            // folder, image, video, pdf, link

            $table->string('file_path')->nullable();
            $table->string('thumb_path')->nullable();
            $table->string('external_url')->nullable();

            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_visual_items');
    }
};