<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\AspirationStatusHistory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirationController extends Controller
{
    /**
     * Display a listing of the student's aspirations.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Aspiration::where('user_id', $userId)
            ->with(['category', 'feedbacks'])
            ->latest();

        // Search within student's own aspirations
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($sub) use ($keyword) {
                $sub->where('ticket_number', 'like', "%{$keyword}%")
                    ->orWhere('title', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $aspirations = $query->paginate(8)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $counts = [
            'total' => Aspiration::where('user_id', $userId)->count(),
            'menunggu' => Aspiration::where('user_id', $userId)->where('status', 'Menunggu')->count(),
            'diproses' => Aspiration::where('user_id', $userId)->where('status', 'Diproses')->count(),
            'selesai' => Aspiration::where('user_id', $userId)->where('status', 'Selesai')->count(),
            'ditolak' => Aspiration::where('user_id', $userId)->where('status', 'Ditolak')->count(),
        ];

        return view('siswa.aspirations.index', compact('aspirations', 'categories', 'counts'));
    }

    /**
     * Show the form for creating a new aspiration.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('siswa.aspirations.create', compact('categories'));
    }

    /**
     * Store a newly created aspiration in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'location' => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['required', 'string', 'min:10', 'max:3000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'], // Max 3MB
        ], [
            'title.required' => 'Judul pengaduan wajib diisi.',
            'title.min' => 'Judul pengaduan minimal 5 karakter.',
            'title.max' => 'Judul pengaduan maksimal 150 karakter.',
            'category_id.required' => 'Silakan pilih kategori sarana dan prasarana.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'location.required' => 'Lokasi fasilitas wajib diisi.',
            'location.min' => 'Lokasi fasilitas minimal 3 karakter.',
            'description.required' => 'Deskripsi pengaduan wajib diisi secara jelas.',
            'description.min' => 'Deskripsi pengaduan minimal 10 karakter.',
            'photo.image' => 'File bukti harus berupa gambar.',
            'photo.mimes' => 'Format foto yang diizinkan adalah JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal adalah 3 MB (3072 KB).',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('aspirations', 'public');
        }

        $aspiration = Aspiration::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'Menunggu',
        ]);

        // Record initial status history
        AspirationStatusHistory::create([
            'aspiration_id' => $aspiration->id,
            'user_id' => Auth::id(),
            'previous_status' => null,
            'new_status' => 'Menunggu',
            'notes' => 'Pengaduan sarana dan prasarana berhasil diajukan oleh siswa.',
        ]);

        return redirect()->route('siswa.aspirasi.show', $aspiration->id)
            ->with('success', 'Aspirasi Anda berhasil dikirim dengan Nomor Tiket: ' . $aspiration->ticket_number . '. Tim Sarpras akan segera memverifikasinya.');
    }

    /**
     * Display the specified aspiration detail for the student.
     */
    public function show(string $id)
    {
        $aspiration = Aspiration::with([
            'category',
            'feedbacks.user',
            'statusHistories.user',
        ])->findOrFail($id);

        // Security check: ensure the student only views their own aspiration
        if ($aspiration->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda hanya dapat melihat aspirasi milik Anda sendiri.');
        }

        return view('siswa.aspirations.show', compact('aspiration'));
    }
}

