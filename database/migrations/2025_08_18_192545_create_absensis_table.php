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
            $table->enum('jenis', ['masuk', 'pulang', 'izin'])->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'pulang', 'izin', 'sakit', 'tidak hadir']);
            $table->string('rfid')->nullable();
            $table->text('keterangan')->nullable();
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
