<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Siswa bermasalah: terlambat atau tidak masuk
        $siswaBermasalah = Siswa::whereNotNull('rfid')
        ->with('kelas')
        ->get()
        ->map(function ($siswa) {
            $siswa->jumlah_terlambat = Absensi::where('siswa_id', $siswa->id)
                ->where('status', 'terlambat')
                ->count();
            return $siswa;
        })
        ->sortByDesc('jumlah_terlambat')
        ->take(10);

    // Siswa rajin: hadir tepat waktu
    $siswaRajin = Siswa::whereNotNull('rfid')
        ->with('kelas')
        ->get()
        ->map(function ($siswa) {
            $siswa->jumlah_hadir = Absensi::where('siswa_id', $siswa->id)
                ->where('status', 'hadir')
                ->count();
            return $siswa;
        })
        ->sortByDesc('jumlah_hadir')
        ->take(10);
    
        // Statistik user
        $stats = [
            'total_users' => User::count(),
            'super_admins' => User::where('role', 'superAdmin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'gurus' => User::where('role', 'guru')->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'users_this_week' => User::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    
        // Recent user
        $recentUsers = User::latest('created_at')->take(10)->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'created_diff' => $user->created_at->diffForHumans(),
                ];
            });
    
        // Trend registrasi 7 hari terakhir
        $registrationTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $registrationTrend[] = [
                'date' => $date->format('Y-m-d'),
                'date_label' => $date->format('M d'),
                'count' => User::whereDate('created_at', $date)->count(),
            ];
        }
    
        // Informasi sistem
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_time' => now()->format('Y-m-d H:i:s'),
            'uptime_since' => Carbon::now()->startOfDay(),
        ];
    
        return view('superAdmin.dashboard', compact(
            'stats',
            'recentUsers',
            'registrationTrend',
            'systemInfo',
            'siswaBermasalah',
            'siswaRajin' // tambahkan siswa rajin
        ));
    }
    

    /**
     * Get user count for AJAX request
     */
    public function getUserCount()
    {
        return response()->json([
            'total' => User::count(),
            'super_admins' => User::where('role', 'superAdmin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'gurus' => User::where('role', 'guru')->count(),
            'today' => User::whereDate('created_at', today())->count(),
        ]);
    }

    /**
     * Get recent activities based on period
     */
    public function getRecentActivities(Request $request)
    {
        $period = $request->get('period', 'today');

        $query = User::query();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
        }

        $activities = $query->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'created_diff' => $user->created_at->diffForHumans(),
                ];
            });

        return response()->json($activities);
    }
}
