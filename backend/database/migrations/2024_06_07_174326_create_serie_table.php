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
        Schema::create('serie', function (Blueprint $table) {
            $table->id('code_serie')->primary();
            $table->string('label_serie');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('serie');
    }
};
