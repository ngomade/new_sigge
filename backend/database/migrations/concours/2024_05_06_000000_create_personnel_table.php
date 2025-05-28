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
        Schema::create('personnel', function (Blueprint $table) {
            $table->char('code_pers', 32)->primary();
            $table->string('nom_pers', 128);
            $table->string('prenom_pers', 128)->nullable();
            $table->char('sexe_pers', 32);
            $table->date('date_naissance_pers');
            $table->string('lieu_naissance_pers', 128);
            $table->char('statut_mat_pers', 32);
            $table->string('lieu_residence_pers', 128)->nullable();
            $table->char('first_phone_pers', 32);
            $table->string('second_phone_pers', 128)->nullable();
            $table->string('cni_pers', 128);
            $table->date('date_deliv_cni_pers');
            $table->string('email_pers', 128);
            $table->string('login_pers', 128);
            $table->string('pwd_pers', 128);
            $table->string('photo_pers', 128)->nullable();
            $table->string('lang_pers', 128)->nullable();
            $table->string('nationalite_pers', 128)->nullable();
            $table->string('region_pers', 128)->nullable();
            $table->string('depart_pers', 128)->nullable();
            $table->string('arrond_pers', 128)->nullable();
            $table->text('bibliographie_pers')->nullable();
            $table->smallInteger('nb_enfant_pers')->nullable();
            
            
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
        Schema::dropIfExists('personnel');
    }
};
