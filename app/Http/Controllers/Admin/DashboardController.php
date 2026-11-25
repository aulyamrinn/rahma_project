<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        $totalAspirations = Aspiration::count();
        $countMenunggu = Aspiration::where('status', 'Menunggu')->count();
        $countDiproses = Aspiration::where('status', 'Diproses')->count();
        $countSelesai = Aspiration::where('status', 'Selesai')->count();
        $countDitolak = Aspiration::where('status', 'Ditolak')->count();
        $totalSiswa = User::where('role', 'siswa')->count();

        // Recent aspirations (eager load user and category)
        $recentAspirations = Aspiration::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get();

        // Category summary
        $categories = Category::withCount('aspirations')->get();
        $categorySummary = $categories->map(function ($cat) use ($totalAspirations) {
            $percentage = $totalAspirations > 0
                ? round(($cat->aspirations_count / $totalAspirations) * 100, 1)
                : 0;

            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'icon' => $cat->icon,
                'count' => $cat->aspirations_count,
                'percentage' => $percentage,
            ];
        });

        // Monthly stats for current year (array processing for UKK compliance)
        $monthlyStats = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthNum = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            $year = now()->year;
            $count = Aspiration::whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->count();
            $monthlyStats[] = [
                'month' => date('M', mktime(0, 0, 0, $m, 1)),
                'count' => $count,
            ];
        }

        return view('admin.dashboard', compact(
            'totalAspirations',
            'countMenunggu',
            'countDiproses',
            'countSelesai',
            'countDitolak',
            'totalSiswa',
            'recentAspirations',
            'categorySummary',
            'monthlyStats'
        ));
    }
}

