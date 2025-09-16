<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Get user statistics
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

        // Get recent activities (recently added users)
        $recentUsers = User::with([])
            ->latest('created_at')
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

        // Get user registration trend (last 7 days)
        $registrationTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $registrationTrend[] = [
                'date' => $date->format('Y-m-d'),
                'date_label' => $date->format('M d'),
                'count' => User::whereDate('created_at', $date)->count(),
            ];
        }

        // System info
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
            'systemInfo'
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
