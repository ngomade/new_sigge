<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participer_projet', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user_ext')->nullable()->after('id_pers_lab');
            $table->foreign('id_user_ext')->references('id_user_ext')->on('user_externe');
        });
    }

    public function down(): void
    {
        Schema::table('participer_projet', function (Blueprint $table) {
            $table->dropForeign(['id_user_ext']);
            $table->dropColumn('id_user_ext');
        });
    }
};
