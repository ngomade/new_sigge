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
        Schema::create('requetes', function (Blueprint $table) {


             $table->string('code_requete',32)->primary();
            $table->string('titre_requete',180);
            $table->string('desc_requete',180);
            $table->string('status')->default('en cours');
            $table->dateTime('date_sousmis');
            $table->dateTime('date_asignation');
            $table->dateTime('date_traitement');
            $table->string('note_interne',191);
            $table->string('code_cat');
            $table->foreign('code_cat')->references('code_cat')->on('categories')->onDelete('cascade');
            $table->string('code_user');
            $table->foreign('code_user')->references('code_user')->on('users')->onDelete('cascade');
            $table->string('code_bureau');
            $table->foreign('code_bureau')->references('code_bureau')->on('bureau')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requetes');
    }
};
