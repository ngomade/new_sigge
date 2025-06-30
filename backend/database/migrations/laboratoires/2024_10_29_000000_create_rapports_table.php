<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id('code_rapport');
            $table->string('libelle_rapport', 255);
            $table->string('type_rapport', 100)->nullable();
            $table->string('path');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
