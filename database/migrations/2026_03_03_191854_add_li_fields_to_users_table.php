<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // id del usuario en la BD central (opcional pero recomendado)
            $table->unsignedBigInteger('li_user_id')->nullable()->index()->after('id');

            // “versión” del password central (normalmente updated_at del registro central)
            $table->timestamp('li_password_updated_at')->nullable()->after('remember_token');

            // para saber que viene de central (opcional)
            $table->boolean('is_li_user')->default(false)->after('li_password_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['li_user_id','li_password_updated_at','is_li_user']);
        });
    }
};