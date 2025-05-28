<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_has_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('id_perm');
            $table->unsignedBigInteger('id_role');
            $table->primary(['id_perm', 'id_role']);
            $table->foreign('id_role')->references('id_role')->on('roles')->onDelete('cascade');
            $table->foreign('id_perm')->references('id_perm')->on('permissions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_has_permission');
    }
};
