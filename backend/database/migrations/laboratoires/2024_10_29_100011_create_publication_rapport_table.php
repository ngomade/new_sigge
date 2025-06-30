<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_rapport', function (Blueprint $table) {
            $table->unsignedBigInteger('code_publi');
            $table->unsignedBigInteger('code_rapport');
            $table->timestamps();

            $table->foreign('code_publi')->references('code_publi')->on('publications');
            $table->foreign('code_rapport')->references('code_rapport')->on('rapports');
            $table->primary(['code_publi', 'code_rapport']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_support');
    }
};
