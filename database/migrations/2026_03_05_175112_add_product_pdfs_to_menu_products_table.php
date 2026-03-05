<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_products', function (Blueprint $table) {
            $table->string('tech_pdf_path')->nullable()->after('url');
            $table->string('manual_pdf_path')->nullable()->after('tech_pdf_path');
        });
    }

    public function down(): void
    {
        Schema::table('menu_products', function (Blueprint $table) {
            $table->dropColumn(['tech_pdf_path', 'manual_pdf_path']);
        });
    }
};