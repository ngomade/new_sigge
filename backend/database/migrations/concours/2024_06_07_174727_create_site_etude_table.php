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
        Schema::create('site_etude', function (Blueprint $table) {
            $table->id('code_site')->primary();
            $table->string('label_site');
            $table->text('description_site');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_etude');
    }
};
