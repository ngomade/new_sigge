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
        Schema::create('bureau', function (Blueprint $table) {
            $table->string('code_bureau', 128)->primary();
            $table->string('label_bureau', 128);
            $table->text('desc_bureau')->nullable();
            $table->string('type_bureau', 128);
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
        Schema::dropIfExists('bureau');
    }
};
