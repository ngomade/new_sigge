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
        if (! Schema::hasTable('reservation_agent')) {

            Schema::table('laboratoire_invitations', function (Blueprint $table) {
                $table->unsignedInteger('nombre_utilisations_max')->default(1)->after('statut');
                $table->unsignedInteger('nombre_utilisations_actuelles')->default(0)->after('nombre_utilisations_max');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('reservation_agent')) {

            Schema::table('laboratoire_invitations', function (Blueprint $table) {
                $table->dropColumn(['nombre_utilisations_max', 'nombre_utilisations_actuelles']);
            });
        }
    }
};
