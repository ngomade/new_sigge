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
        Schema::create('semestre_niveau', function (Blueprint $table) {
            $table->char('code_niveau', 32);
            $table->string('code_sem', 10);
             $table->foreign('code_niveau')->references('code_niveau')->on('niveau')->onDelete('cascade');
              $table->foreign('code_sem')->references('code_sem')->on('semestre')->onDelete('cascade');

            $table->primary(['code_niveau', 'code_sem']);
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
        Schema::dropIfExists('semestre_niveau');
    }
};
