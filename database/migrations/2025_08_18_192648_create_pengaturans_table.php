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
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk_awal')->default('05:00'); // batas awal boleh masuk
            $table->time('jam_masuk_akhir')->default('07:00'); // batas akhir masuk
            $table->time('jam_pulang')->default('15:00'); // hanya satu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
