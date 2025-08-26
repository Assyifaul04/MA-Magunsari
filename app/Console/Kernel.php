<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateAbsentRecords;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command custom
     */
    protected $commands = [
        GenerateAbsentRecords::class,
    ];

    /**
     * Schedule command
     */
    protected function schedule(Schedule $schedule)
    {
        // Jalankan command otomatis setiap hari jam 00:01
        $schedule->command('absensi:generate-tidak-hadir')->dailyAt('00:01');
    }

    /**
     * Register command bawaan aplikasi
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
