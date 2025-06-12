<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRessourceActuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ressource_actu', function (Blueprint $table) {
            $table->integer('r_id', true);
            $table->string('actu_code', 128);
            $table->foreign('actu_code')->references("actu_code")->on("actualite");
            $table->string('r_type', 128);
            $table->string('r_name', 128);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ressource_actu');
    }
}
