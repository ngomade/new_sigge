<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quitus', function (Blueprint $table) {
            $table->char('code_ins', 32);
            $table->integer('code_tranche')->index('fk_quitus_tranche');
            $table->smallInteger('code_mode')->index('fk_quitus_modepaiment');
            $table->string('numero_quitus', 128);
            $table->date('date_paiement');
            $table->smallInteger('statut_quitus');

            $table->primary(['code_ins', 'code_tranche', 'code_mode']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quitus');
    }
};
