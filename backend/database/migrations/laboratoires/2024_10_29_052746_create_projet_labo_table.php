<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetLaboTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('projet_labo', function (Blueprint $table) {
            $table->id("code_projet");
            $table->string("theme_projet");
            $table->text("description_projet");
            $table->string("code_lab");

            $table->foreign("code_lab")->references("code_lab")->on("laboratoire");
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
        Schema::dropIfExists('projet_labo');
    }
}
