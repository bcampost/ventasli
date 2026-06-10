<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menu_nodes', function (Blueprint $table) {
            // ✅ Nuevos: cards | Existentes: los pasamos a classic
            $table->string('view_mode', 20)->default('cards')->after('is_active');
        });

        // ✅ Respeta todo lo actual: lo ya creado queda en classic
        DB::table('menu_nodes')->update(['view_mode' => 'classic']);
    }

    public function down(): void
    {
        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->dropColumn('view_mode');
        });
    }
};