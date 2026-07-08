<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama');

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->onDelete('cascade');

            $table->year('angkatan')->nullable();

            $table->foreignId('orang_tua_id')
                ->nullable()
                ->constrained('orang_tuas')
                ->nullOnDelete();

            $table->string('rfid')->nullable()->unique();
            $table->enum('status', ['aktif', 'pending', 'non_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
