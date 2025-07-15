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
            if (Schema::hasColumn('user_externe', 'motivation')) {
                $table->renameColumn('motivation', 'motivation_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_externe', function (Blueprint $table) {
            if (Schema::hasColumn('user_externe', 'motivation_path')) {
                $table->renameColumn('motivation_path', 'motivation');
            }
        });
    }
};
