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
        Schema::create('template_whatsapps', function (Blueprint $table) {
            $table->id();

            $table->string('nama_template');

            $table->enum('jenis', [
                'masuk',
                'pulang',
                'izin',
                'sakit',
                'terlambat'
            ]);

            $table->index('jenis');

            $table->text('isi_pesan');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_whatsapps');
    }
};
