<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_notif', function (Blueprint $table) {
            $table->id('id_notif');
            $table->string('code_lab', 10);
            $table->string('id_pers_lab_expediteur');
            $table->string('id_pers_lab_destinataire')->nullable();
            $table->string('type', 50);
            $table->string('titre', 255);
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->timestamps();

            $table->foreign('code_lab')->references('code_lab')->on('laboratoire');
            $table->foreign('id_pers_lab_expediteur')->references('id_pers_lab')->on('pers_lab');
            $table->foreign('id_pers_lab_destinataire')->references('id_pers_lab')->on('pers_lab');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_notif');
    }
};
