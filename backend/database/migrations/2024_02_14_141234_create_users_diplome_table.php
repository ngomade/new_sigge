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
        Schema::create('users_diplome', function (Blueprint $table) {
            $table->char('code_user', 32);
            $table->unsignedBigInteger('code_dip');
            $table->foreign('code_dip')->references('code_dip')->on('diplome')->onDelete('cascade');
            $table->foreign('code_user')->references('code_user')->on('users')->onDelete('cascade');
            $table->date('annee_dip');
            $table->string('institution_dip', 128);
            $table->string('mention_dip', 128);
            $table->string('pays_dip', 128);

            $table->primary(['code_user', 'code_dip']);
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
        Schema::dropIfExists('users_diplome');
    }
};
