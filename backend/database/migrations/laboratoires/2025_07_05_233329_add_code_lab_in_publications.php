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
        Schema::table('publications', function (Blueprint $table) {
            $table->string('code_lab', 20)->after('code_publi')->nullable();
            $table->string('reference')->nullable();
            $table->string('rapport_path')->nullable();
            $table->foreign('code_lab')->references('code_lab')->on('laboratoire')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropForeign(['code_lab']);
            $table->dropColumn('reference');
            $table->dropColumn('rapport_path');
            $table->dropColumn('code_lab');
        });
    }
};
