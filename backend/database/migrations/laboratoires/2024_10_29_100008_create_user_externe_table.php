<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_externe', function (Blueprint $table) {
            $table->id('id_user_ext');
            $table->string('code_lab');
            $table->string('nom_user_ext', 191);
            $table->string('prenom_user_ext', 191);
            $table->string('email_user_ext', 191)->unique();
            $table->string('tel_user_ext', 50);
            $table->string('statut', 50)->nullable();
            $table->string('pwd')->nullable();
            $table->string('logo_url')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_externe');
    }
};
