<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_whatsapps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');

            $table->enum('jenis', [
                'rekap_harian',
            ])->default('rekap_harian');

            $table->index('jenis');
            $table->text('isi_pesan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_whatsapps');
    }
};