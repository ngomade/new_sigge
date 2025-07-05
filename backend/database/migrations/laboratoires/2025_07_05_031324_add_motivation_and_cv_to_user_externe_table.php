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
        Schema::table('user_externe', function (Blueprint $table) {
            $table->text('motivation')->nullable()->after('tel_user_ext');
            $table->string('cv_path')->nullable()->after('motivation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_externe', function (Blueprint $table) {
            $table->dropColumn(['motivation', 'cv_path']);
        });
    }
};
