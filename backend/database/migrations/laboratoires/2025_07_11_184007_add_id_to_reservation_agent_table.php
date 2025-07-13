<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdToReservationAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Vérifier si la colonne 'id' existe déjà
        if (!Schema::hasColumn('reservation_agent', 'id')) {
            Schema::table('reservation_agent', function (Blueprint $table) {
                // Ajouter la colonne id auto-incrémentée en premier
                $table->id()->first();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_agent', function (Blueprint $table) {
            if (Schema::hasColumn('reservation_agent', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
}