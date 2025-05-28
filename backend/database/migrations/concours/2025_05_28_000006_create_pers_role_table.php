<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->String('code_pers');
            $table->unsignedBigInteger('id_role');
            $table->primary(['id_role', 'code_pers']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->String('statut_role');

           
            $table->foreign('code_pers')->references('code_pers')->on('personnel')->onDelete('cascade');
            $table->foreign('id_role')->references('id_role')->on('roles')->onDelete('cascade');
            $table->timestamps();
           
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_has_permissions');
    }
};
