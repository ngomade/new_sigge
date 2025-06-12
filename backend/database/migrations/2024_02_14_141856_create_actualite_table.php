<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActualiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('actualite', function (Blueprint $table) {
            $table->string('actu_code', 128)->primary();
            $table->char('code_pers', 32);
            $table->foreign("code_pers")->references("code_pers")->on("personnel");
            $table->text('actu_title');
            $table->text('actu_content');
            $table->boolean('actu_status')->nullable();
            $table->bigInteger('actu_nb_views')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('actualite');
    }
}
