<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Change tokenable_id from integer to string
            $table->string('tokenable_id',100)->change();
        });
    }

    public function down()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Reverse the change
            $table->unsignedBigInteger('tokenable_id')->change();
        });
    }
};
