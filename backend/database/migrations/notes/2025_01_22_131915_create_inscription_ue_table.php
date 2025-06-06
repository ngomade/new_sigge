<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscriptionUeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscription_ue', function (Blueprint $table) {
            $table->char('code_ins', 32);
            $table->char('code_ue', 32);
            $table->integer("etat")->default(0);
            $table->primary(["code_ins", "code_ue"]);
            $table->foreign('code_ins' )->references('code_ins')->on('inscription');
            $table->foreign('code_ue' )->references(['code_ue'])->on('ue');
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
        Schema::dropIfExists('inscription_ue');
    }
}
