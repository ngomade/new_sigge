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
        Schema::create('centre_examen', function (Blueprint $table) {
            $table->id('centre_exam_code')->primary();
            $table->string('code_ecole',180);
            $table->string('centre_exam_label');
            $table->foreign('code_ecole')->references('code_ecole')->on('ecole')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centre_examen');
    }
};
