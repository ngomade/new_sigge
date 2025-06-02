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
        Schema::create('compte', function (Blueprint $table) {
            $table->string('ca_num_recu', 40)->primary();
             $table->string('ca_code', 20)->nullable();
            $table->string('ca_pwd');
            $table->string('ca_recu');
            $table->string('ca_nom');
            $table->string('ca_email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('reset_token')->nullable()->unique();
            $table->timestamp('reset_token_expires_at')->nullable();
            $table->string('ca_prenom');
            $table->timestamps();

             $table->foreign('ca_code')->references('ca_code')->on('candidat')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compte');
    }
};
