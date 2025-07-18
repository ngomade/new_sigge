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
        Schema::create('labo_chats', function (Blueprint $table) {
            $table->id();
            $table->string('code_lab'); // Code du laboratoire
            $table->unsignedBigInteger('id_expediteur'); // ID du membre qui envoie
            $table->string('type_expediteur'); // Type du membre (personnel, user, externe)
            $table->text('message');
            $table->timestamps();

            $table->index('code_lab');
            $table->index(['code_lab', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labo_chats');
    }
};
