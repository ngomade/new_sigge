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
        Schema::create('filiere', function (Blueprint $table) {
            $table->string('code_filiere', 20)->primary();
            $table->string('code_bureau', 128)->constrained('bureau', 'code_bureau');
            $table->string('label_filiere');
            $table->text('desc_filiere')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('filiere');
    }
};
