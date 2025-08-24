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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null');
            $table->enum('jenis', ['masuk', 'pulang', 'izin']);
            $table->enum('status', ['hadir', 'terlambat', 'pulang', 'izin', 'sakit', 'tidak hadir']);
            $table->string('rfid')->nullable(); // untuk absensi izin atau siswa tanpa data lengkap
            $table->text('keterangan')->nullable(); // untuk izin
            $table->date('tanggal');
            $table->time('jam');
            $table->timestamps();
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
