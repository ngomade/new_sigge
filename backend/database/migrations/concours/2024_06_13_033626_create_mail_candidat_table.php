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
        Schema::create('mail_candidat', function (Blueprint $table) {
            $table->id('pk_mail_candidat');
            $table->string('lca_code', 20);
            $table->unsignedBigInteger('email_code');
            $table->timestamps();

            $table->foreign('lca_code')->references('ca_code')->on('candidat')->onDelete('cascade');
            $table->foreign('email_code')->references('email_code')->on('mails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_candidat');
    }
};
