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
        Schema::create('candidat', function (Blueprint $table) {
            $table->string('ca_code', 20)->primary();
            $table->unsignedBigInteger('id');
            $table->string('filiere_code', 20);
            $table->unsignedBigInteger('code_site');
            $table->string('ca_nom');
            $table->string('ca_prenom');
            $table->string('ca_sexe');
            $table->date('ca_date_naiss');
            $table->string('ca_lieu_naiss');
            $table->string('ca_statut_mat');
            $table->string('ca_adresse')->nullable();
            $table->string('ca_telephone',180)->unique();
            $table->string('ca_num_cni',180)->unique();
            $table->string('ca_email');
            $table->string('ca_premiere_lang');
            $table->string('ca_nationalite');
            $table->string('ca_region_origine');
            $table->string('ca_depart_origine');
            $table->string('ca_diplome_admission');
            $table->year('ca_annee_diplome');
            $table->string('ca_serie_diplome');
            $table->string('ca_mention_diplome');
            $table->string('ca_etab_diplome');
            $table->string('ca_pays_diplome');
            $table->string('ca_centre_examen');
            $table->string('ca_centre_depot');
            $table->string('ca_nom_pere');
            $table->string('ca_telephone_pere',180);
            $table->string('ca_nom_mere');
            $table->string('ca_telephone_mere',180);
            $table->string('ca_handicap');
            $table->string('ca_email_pere')->nullable();
            $table->string('ca_deliv_cni');
            $table->string('ca_num_recu');
            $table->string('ca_recu');
            $table->timestamps();

            $table->foreign('filiere_code')->references('filiere_code')->on('filiere')->onDelete('cascade');
            $table->foreign('code_site')->references('code_site')->on('site_etude')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id')->references('id')->on('sessionconcour')->onDelete('restrict')->onUpdate("cascade");
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidat');
    }
};
