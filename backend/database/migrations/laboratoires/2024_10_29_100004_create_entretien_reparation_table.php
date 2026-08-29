<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entretien_reparation', function (Blueprint $table) {
            $table->unsignedBigInteger('code_equip');
            $table->string('id_pers_lab');
            $table->enum('statut_entretien', ['En cours', 'Terminé', 'En pause', 'Annulé'])->default('En cours');
            $table->date('debut_entretien');
            $table->date('fin_entretien');
            $table->string('type_entretien', 50); // entretien ou reparation
            $table->text('desc_entretien')->nullable();
            $table->decimal('cout', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('code_equip')->references('code_equip')->on('equipements');
            $table->foreign('id_pers_lab')->references('id_pers_lab')->on('pers_lab');

            $table->primary(['code_equip', 'id_pers_lab']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entretien_reparation');
    }
};
