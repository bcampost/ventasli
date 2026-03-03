<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('footer_links', function (Blueprint $table) {

            if (!Schema::hasColumn('footer_links', 'link_mode')) {
                $table->string('link_mode', 20)->default('menu')->after('label')->index();
            }

            if (!Schema::hasColumn('footer_links', 'file_path')) {
                $table->string('file_path', 1024)->nullable()->after('external_url');
            }
            if (!Schema::hasColumn('footer_links', 'file_name')) {
                $table->string('file_name', 255)->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('footer_links', 'file_mime')) {
                $table->string('file_mime', 120)->nullable()->after('file_name');
            }
            if (!Schema::hasColumn('footer_links', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('file_mime');
            }
            if (!Schema::hasColumn('footer_links', 'file_disk')) {
                $table->string('file_disk', 40)->nullable()->after('file_size');
            }
        });
    }

    public function down(): void
    {
        Schema::table('footer_links', function (Blueprint $table) {
            // Solo dropea si existen (por si tu BD no tiene todo)
            $cols = ['link_mode','file_path','file_name','file_mime','file_size','file_disk'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('footer_links', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};