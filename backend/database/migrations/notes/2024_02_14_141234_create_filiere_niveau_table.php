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
        Schema::create('filiere_niveau', function (Blueprint $table) {
             $table->char('filiere_code', 32);
            $table->char('code_niveau', 32);
            $table->char('code_ins', 32);
             $table->foreign('filiere_code')->references('filiere_code')->on('filiere')->onDelete('cascade');
              $table->foreign('code_niveau')->references('code_niveau')->on('niveau')->onDelete('cascade');
               $table->foreign('code_ins')->references('code_ins')->on('inscription')->onDelete('cascade');

            $table->primary([ 'filiere_code', 'code_niveau', 'code_ins']);
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
        Schema::dropIfExists('filiere_niveau');
    }
};
