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
        Schema::create('master_kks', function (Blueprint $table) {
            $table->id('id_kk');
            $table->uuid('uuid');
            $table->bigInteger('no_kk')->nullable();
            $table->unique('no_kk');
            $table->string('alamat', 100)->nullable();
            $table->tinyInteger('rt');
            $table->tinyInteger('rw');
            $table->integer('kode_pos');
            $table->string('kelurahan', 60)->nullable();
            $table->string('kecamatan', 60)->nullable();
            $table->string('kabupaten', 60)->nullable();
            $table->string('provinsi', 60)->nullable();
            $table->string('kk_tgl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kks');
    }
};
