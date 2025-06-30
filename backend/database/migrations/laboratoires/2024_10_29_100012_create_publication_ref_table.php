<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_ref', function (Blueprint $table) {
            $table->unsignedBigInteger('code_ref');
            $table->unsignedBigInteger('code_publi');
            $table->timestamps();

            $table->foreign('code_ref')->references('code_ref')->on('references');
            $table->foreign('code_publi')->references('code_publi')->on('publications');
            $table->primary(['code_ref', 'code_publi']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_ref');
    }
};
