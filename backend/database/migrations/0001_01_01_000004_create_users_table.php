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
        Schema::create('users', function (Blueprint $table) {
            $table->char('code_user', 32)->primary();
            $table->unsignedInteger('code_info_extra');
            $table->foreign('code_info_extra')->references('code_info_extra')->on('info_extra')->onDelete('cascade');
            $table->string('nom_user', 128);
            $table->string('prenom_user', 128)->nullable();
            $table->string('sexe_user', 128);
            $table->date('date_naissance_user');
            $table->string('lieu_naissance_user', 128);
            $table->string('statut_mat_user', 128);
            $table->string('lieu_resi_user', 128)->nullable();
            $table->string('first_phone_user', 20);
            $table->string('second_phone_user', 128)->nullable();
            $table->string('numero_cni_user', 128);
            $table->string('email_user', 128);
            $table->date('date_deliv_cni_user');
            $table->string('login_user', 128);
            $table->string('pwd_user', 128);
            $table->string('photo_user', 128)->nullable();
            $table->string('handicap_user', 128)->nullable();
            $table->string('langue_user', 128)->nullable();
            $table->smallInteger('nbre_enfant_user')->default(0);
            $table->string('nationalite_user', 128)->nullable();
            $table->string('region_origine_user', 128)->nullable();
            $table->string('depart_origine_user', 128)->nullable();
            $table->string('arrond_origine_user', 128)->nullable();
            $table->text('bibiographie_user')->nullable();
            $table->smallInteger('statut_user')->default(1);
            $table->string('ecole_user', 128)->default('ESTLC');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
