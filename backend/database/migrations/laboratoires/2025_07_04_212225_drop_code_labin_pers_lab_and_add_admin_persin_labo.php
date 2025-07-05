<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pers_lab', function (Blueprint $table) {
            $table->dropForeign(['code_lab']);
            $table->dropColumn('code_lab');
        });
        Schema::table('laboratoire', function (Blueprint $table) {
            $table->string('admin_pers_labo', 50)->nullable();
            $table->foreign('admin_pers_labo')->references('id_pers_lab')->on('pers_lab')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pers_lab', function (Blueprint $table) {
            $table->string('code_lab', 50)->nullable();
            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('set null');
        });
        Schema::table('laboratoire', function (Blueprint $table) {
            $table->dropForeign(['admin_pers_labo']);
            $table->dropColumn('admin_pers_labo');
        });
    }
};
