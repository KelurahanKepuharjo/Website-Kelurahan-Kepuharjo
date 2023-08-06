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
        Schema::table('master_masyarakats', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kk');
            $table->foreign('id_kk')->references('id_kk')->on('master_kks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_masyarakats', function (Blueprint $table) {
            $table->dropForeign(['id_kk']);
            $table->dropColumn('id_kk');
        });
    }
};
