<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipements', function (Blueprint $table) {
            $table->id('code_equip');
            $table->string('nom_equip', 150);
            $table->string('ref_equip', 100)->nullable();
            $table->text('desc_equip')->nullable();
            $table->string('etat', 50)->default('disponible');
            $table->date('date_achat')->nullable();
            $table->decimal('valeur', 12, 2)->nullable();
            $table->string('localisation', 150)->nullable();
            $table->string('code_lab', 10);
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
