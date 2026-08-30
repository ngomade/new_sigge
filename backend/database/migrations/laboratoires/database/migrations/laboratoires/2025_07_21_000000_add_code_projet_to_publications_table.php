<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->integer('code_projet')->nullable()->after('code_lab')->unsigned();
            $table->foreign('code_projet')->references('code_projet')->on('projet_labo')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropForeign(['code_projet']);
            $table->dropColumn('code_projet');
        });
    }
};
