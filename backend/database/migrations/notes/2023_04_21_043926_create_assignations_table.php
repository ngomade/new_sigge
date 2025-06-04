<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignations', function (Blueprint $table) {
            $table->id('code_ass');
             $table->char('code_ec', 32);
             $table->foreign('code_ec')->references('code_ec')->on('ec')->onDelete('cascade');
              $table->string('code_class');
            $table->foreign('code_class')->references('code_class')->on('classes')->onDelete('cascade');

            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignations');
    }
};
