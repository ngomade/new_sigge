<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labo_media', function (Blueprint $table) {
            $table->id('id_media');
            $table->string('code_lab', 10);
            $table->string('type', 50); // image, video, document
            $table->string('url', 255);
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labo_media');
    }
};
