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
        Schema::create('composition', function (Blueprint $table) {
            $table->string('code_ecole', 100);
            $table->string('site_code', 100);
            $table->primary(['code_ecole', 'site_code']);
            $table->foreign('code_ecole')->references('code_ecole')->on('ecole')->onDelete('cascade');
            $table->foreign('site_code')->references('site_code')->on('site_composition')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition');
    }
};
