<?php

// database/migrations/2026_03_XX_XXXXXX_add_file_fields_to_footer_links_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('footer_links', function (Blueprint $table) {
      if (!Schema::hasColumn('footer_links', 'link_mode')) {
        $table->string('link_mode', 20)->default('menu')->after('label'); // menu|external|file
      }

      if (!Schema::hasColumn('footer_links', 'file_path')) {
        $table->string('file_path', 255)->nullable()->after('external_url');
      }
      if (!Schema::hasColumn('footer_links', 'file_disk')) {
        $table->string('file_disk', 40)->default('public')->after('file_path');
      }
      if (!Schema::hasColumn('footer_links', 'file_original')) {
        $table->string('file_original', 255)->nullable()->after('file_disk');
      }
      if (!Schema::hasColumn('footer_links', 'file_mime')) {
        $table->string('file_mime', 120)->nullable()->after('file_original');
      }
      if (!Schema::hasColumn('footer_links', 'file_size')) {
        $table->unsignedBigInteger('file_size')->nullable()->after('file_mime');
      }
    });
  }

  public function down(): void {
    Schema::table('footer_links', function (Blueprint $table) {
      foreach (['link_mode','file_path','file_disk','file_original','file_mime','file_size'] as $col) {
        if (Schema::hasColumn('footer_links', $col)) $table->dropColumn($col);
      }
    });
  }
};