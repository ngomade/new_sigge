<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_labo', function (Blueprint $table) {
            $table->string('code_rl')->primary();
            $table->string('path_rl');
            $table->text('desc_rapport')->nullable();
            $table->string('code_lab');
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_labo');
    }
};
