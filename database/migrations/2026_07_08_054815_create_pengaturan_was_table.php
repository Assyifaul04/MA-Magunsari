<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_wa', function (Blueprint $table) {
            $table->id();
            $table->string('fonnte_token')->nullable();
            $table->string('nama_perangkat')->nullable();
            $table->string('nomor_wa')->nullable();
            $table->string('status_koneksi')->default('disconnect');
            $table->integer('sisa_kuota')->default(0);
            $table->string('masa_aktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_wa');
    }
};