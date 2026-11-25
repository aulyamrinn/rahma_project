<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application landing page or redirect to appropriate dashboard.
     */
    public function index()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('siswa.dashboard');
        }

        $categories = Category::where('is_active', true)->get();
        $totalAspirations = Aspiration::count();
        $totalResolved = Aspiration::where('status', 'Selesai')->count();
        $totalInProcess = Aspiration::where('status', 'Diproses')->count();

        return view('welcome', compact('categories', 'totalAspirations', 'totalResolved', 'totalInProcess'));
    }
}

