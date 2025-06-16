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
        Schema::create('users_role', function (Blueprint $table) {
            $table->char('code_user', 32);
             $table->unsignedBigInteger('id');
             $table->foreign('code_user')->references('code_user')->on('users')->onDelete('cascade');
               $table->foreign('id')->references('id')->on('roles')->onDelete('cascade');
            // $table->date('annee_dip');
           
            $table->date('date_debut_role');
            $table->date('date_fin_role')->nullable();
            $table->integer('etat_role');

            $table->primary(['code_user','id']);
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
        Schema::dropIfExists('users_role');
    }
};
