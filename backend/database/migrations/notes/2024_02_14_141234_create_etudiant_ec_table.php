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
        Schema::create('etudiant_ec', function (Blueprint $table) {
            $table->char('code_user', 32);
            $table->char('code_ec', 32);
             $table->foreign('code_ec')->references('code_ec')->on('ec')->onDelete('cascade');
              $table->foreign('code_user')->references('code_user')->on('user')->onDelete('cascade');
            $table->primary(['code_user', 'code_ec']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etudiant_ec');
    }
};
