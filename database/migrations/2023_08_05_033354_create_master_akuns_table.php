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
        Schema::create('master_akuns', function (Blueprint $table) {
            $table->id('akun_id');
            $table->uuid('uuid');
            $table->string('password', 255)->nullable()->default('text');
            $table->String('no_hp')->nullable()->default(13);
            $table->string('role', 12)->nullable()->default('text');
            $table->string('fcm_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_akuns');
    }
};
