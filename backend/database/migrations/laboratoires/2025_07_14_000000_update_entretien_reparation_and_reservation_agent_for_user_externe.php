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
        // Table entretien_reparation
        Schema::table('entretien_reparation', function (Blueprint $table) {
            if (! Schema::hasColumn('entretien_reparation', 'id_user_ext')) {
                $table->unsignedBigInteger('id_user_ext')->nullable()->after('id_pers_lab');
                $table->foreign('id_user_ext')->references('id_user_ext')->on('user_externe')->onDelete('set null');
            }
            $table->string('id_pers_lab')->nullable()->change();
        });

        // Table reservation_agent
        Schema::table('reservation_agent', function (Blueprint $table) {
            if (! Schema::hasColumn('reservation_agent', 'id_user_ext')) {
                $table->unsignedBigInteger('id_user_ext')->nullable()->after('id_pers_lab');
                $table->foreign('id_user_ext')->references('id_user_ext')->on('user_externe')->onDelete('set null');
            }
            $table->string('id_pers_lab')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Table entretien_reparation
        Schema::table('entretien_reparation', function (Blueprint $table) {
            if (Schema::hasColumn('entretien_reparation', 'id_user_ext')) {
                $table->dropForeign(['id_user_ext']);
                $table->dropColumn('id_user_ext');
            }
            $table->string('id_pers_lab')->nullable(false)->change();
        });

        // Table reservation_agent
        Schema::table('reservation_agent', function (Blueprint $table) {
            if (Schema::hasColumn('reservation_agent', 'id_user_ext')) {
                $table->dropForeign(['id_user_ext']);
                $table->dropColumn('id_user_ext');
            }
            $table->string('id_pers_lab')->nullable(false)->change();
        });
    }
};
