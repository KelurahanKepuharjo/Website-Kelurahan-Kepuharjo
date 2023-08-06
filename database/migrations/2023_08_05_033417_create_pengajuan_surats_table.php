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
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->uuid('uuid');
            $table->String('nomor_surat')->nullable();
            $table->String('no_pengantar')->nullable();
            $table->string('status', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_ditolak')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('image_kk')->nullable();
            $table->string('image_bukti')->nullable();
            $table->enum('info', ['active', 'non_active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};
