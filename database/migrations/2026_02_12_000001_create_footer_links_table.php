<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('footer_links', function (Blueprint $table) {
            $table->id();

            // grupo: "capacitaciones", "ubicaciones", "social" (por si luego lo amplías)
            $table->string('group', 40)->index();

            // key estable: videos, presentaciones, etc.
            $table->string('key', 80)->index();

            $table->string('label', 120);

            // Si el tipo es "menu", se arma como /menu/{menu_path}
            $table->string('menu_path', 255)->nullable();

            // Si el tipo es "external", se usa tal cual
            $table->string('external_url', 2048)->nullable();

            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['group','key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_links');
    }
};