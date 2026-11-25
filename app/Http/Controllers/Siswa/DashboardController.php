<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $userId = Auth::id();

        $totalAspirations = Aspiration::where('user_id', $userId)->count();
        $countMenunggu = Aspiration::where('user_id', $userId)->where('status', 'Menunggu')->count();
        $countDiproses = Aspiration::where('user_id', $userId)->where('status', 'Diproses')->count();
        $countSelesai = Aspiration::where('user_id', $userId)->where('status', 'Selesai')->count();
        $countDitolak = Aspiration::where('user_id', $userId)->where('status', 'Ditolak')->count();

        // Recent aspirations by this student
        $recentAspirations = Aspiration::where('user_id', $userId)
            ->with(['category', 'feedbacks.user'])
            ->latest()
            ->take(5)
            ->get();

        // Recent admin feedbacks on student's aspirations
        $recentFeedbacks = Feedback::whereHas('aspiration', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['aspiration', 'user'])
        ->latest()
        ->take(4)
        ->get();

        return view('siswa.dashboard', compact(
            'totalAspirations',
            'countMenunggu',
            'countDiproses',
            'countSelesai',
            'countDitolak',
            'recentAspirations',
            'recentFeedbacks'
        ));
    }
}

