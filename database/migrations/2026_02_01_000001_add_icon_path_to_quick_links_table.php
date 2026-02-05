<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('quick_links', 'icon_path')) {
            Schema::table('quick_links', function (Blueprint $table) {
                $table->string('icon_path')->nullable()->after('icon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('quick_links', 'icon_path')) {
            Schema::table('quick_links', function (Blueprint $table) {
                $table->dropColumn('icon_path');
            });
        }
    }
};