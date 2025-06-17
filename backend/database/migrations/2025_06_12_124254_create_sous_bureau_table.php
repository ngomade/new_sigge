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
        Schema::create('sous_bureau', function (Blueprint $table) {
            $table->string('code_bureau');
            $table->string('code_sous_bureau');
            $table->foreign('code_bureau')->references
            ('code_bureau')->on('bureau')->onDelete('cascade');
             $table->foreign('code_sous_bureau')->references
            ('code_bureau')->on('bureau')->onDelete('cascade');
            $table->primary(['code_bureau', 'code_sous_bureau']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_bureau');
    }
};
