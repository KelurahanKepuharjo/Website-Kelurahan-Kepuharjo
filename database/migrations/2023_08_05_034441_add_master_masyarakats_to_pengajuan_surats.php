<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->unsignedBigInteger('id_masyarakat');
            $table->foreign('id_masyarakat')->references('id_masyarakat')->on('master_masyarakats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropForeign(['id_masyarakat']);
            $table->dropColumn('id_masyarakat');
        });
    }
};
