<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('menu_product_details', function (Blueprint $table) {
        $table->string('tech_pdf_path')->nullable()->after('height');
        $table->string('manual_pdf_path')->nullable()->after('tech_pdf_path');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_product_details', function (Blueprint $table) {

            $table->dropColumn([
                'pdf_ficha_path',
                'pdf_instructivo_path'
            ]);

        });
    }
};