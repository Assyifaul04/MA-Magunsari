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
        Schema::create('notifikasi_whatsapps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('absensi_id')
                ->nullable()
                ->constrained('absensis')
                ->nullOnDelete();

            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswas')
                ->nullOnDelete();

            $table->foreignId('orang_tua_id')
                ->nullable()
                ->constrained('orang_tuas')
                ->nullOnDelete();

            $table->string('nomor_whatsapp');

            $table->text('pesan');

            $table->enum('status', [
                'pending',
                'terkirim',
                'gagal'
            ])->default('pending');

            $table->text('response_gateway')->nullable();

            $table->timestamp('dikirim_pada')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_whatsapps');
    }
};
