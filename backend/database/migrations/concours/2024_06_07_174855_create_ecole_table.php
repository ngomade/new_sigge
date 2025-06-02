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
        Schema::create('ecole', function (Blueprint $table) {
            $table->string('code_ecole', 100)->primary();
            $table->string('label_ecole');
            $table->string('logo_ecole');
            $table->text('desc_ecole');
            $table->string('tel_ecole',180);
            $table->string('email_ecole')->nullable();
            $table->string('bp_ecole');
            $table->unsignedBigInteger('centre_depot_code');
            $table->foreign('centre_depot_code')->references('centre_depot_code')->on('centre_depot')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ecole');
    }
};
