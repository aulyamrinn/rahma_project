<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\AspirationStatusHistory;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AspirationController extends Controller
{
    /**
     * Display a listing of aspirations with filtering and searching.
     */
    public function index(Request $request)
    {
        $aspirations = $this->buildFilteredQuery($request)->paginate(10)->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $students = User::where('role', 'siswa')->orderBy('name')->get();

        // Status counts for filter pills
        $counts = [
            'total' => Aspiration::count(),
            'menunggu' => Aspiration::where('status', 'Menunggu')->count(),
            'diproses' => Aspiration::where('status', 'Diproses')->count(),
            'selesai' => Aspiration::where('status', 'Selesai')->count(),
            'ditolak' => Aspiration::where('status', 'Ditolak')->count(),
        ];

        return view('admin.aspirations.index', compact('aspirations', 'categories', 'students', 'counts'));
    }

    /**
     * Render a print-ready PDF report of filtered aspirations.
     */
    public function printPdf(Request $request)
    {
        $aspirations = $this->buildFilteredQuery($request)->get();

        $summary = [
            'total' => $aspirations->count(),
            'menunggu' => $aspirations->where('status', 'Menunggu')->count(),
            'diproses' => $aspirations->where('status', 'Diproses')->count(),
            'selesai' => $aspirations->where('status', 'Selesai')->count(),
            'ditolak' => $aspirations->where('status', 'Ditolak')->count(),
        ];

        $filters = [];
        if ($request->filled('q')) {
            $filters[] = 'Kata Kunci: "' . trim($request->input('q')) . '"';
        }
        if ($request->filled('status')) {
            $filters[] = 'Status: ' . $request->input('status');
        }
        if ($request->filled('category_id')) {
            $category = Category::find($request->input('category_id'));
            if ($category) {
                $filters[] = 'Kategori: ' . $category->name;
            }
        }
        if ($request->filled('user_id')) {
            $student = User::find($request->input('user_id'));
            if ($student) {
                $filters[] = 'Siswa: ' . $student->name;
            }
        }
        if ($request->filled('date')) {
            $filters[] = 'Tanggal: ' . \Carbon\Carbon::parse($request->input('date'))->translatedFormat('d F Y');
        }
        if ($request->filled('month')) {
            $parts = explode('-', $request->input('month'));
            if (count($parts) === 2) {
                $filters[] = 'Bulan: ' . \Carbon\Carbon::createFromDate((int) $parts[0], (int) $parts[1], 1)->translatedFormat('F Y');
            }
        }

        $pdf = Pdf::loadView('admin.aspirations.pdf', compact('aspirations', 'summary', 'filters'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-pengaduan-schoolcare-' . now()->format('Ymd-His') . '.pdf');
    }

    /**
     * Build filtered aspiration query shared by index and PDF export.
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Aspiration::with(['user', 'category'])->latest();

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($sub) use ($keyword) {
                $sub->where('ticket_number', 'like', "%{$keyword}%")
                    ->orWhere('title', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('nisn', 'like', "%{$keyword}%")
                            ->orWhere('kelas', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        if ($request->filled('month')) {
            $parts = explode('-', $request->input('month'));
            if (count($parts) === 2) {
                $query->whereYear('created_at', $parts[0])
                    ->whereMonth('created_at', $parts[1]);
            }
        }

        return $query;
    }

    /**
     * Display the specified aspiration detail.
     */
    public function show(string $id)
    {
        $aspiration = Aspiration::with([
            'user',
            'category',
            'feedbacks.user',
            'statusHistories.user',
        ])->findOrFail($id);

        return view('admin.aspirations.show', compact('aspiration'));
    }

    /**
     * Update the status of the aspiration and record history.
     */
    public function updateStatus(Request $request, string $id)
    {
        $aspiration = Aspiration::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:Menunggu,Diproses,Selesai,Ditolak'],
            'notes' => ['nullable', 'string', 'max:500'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ], [
            'status.required' => 'Pilih status baru yang valid.',
            'status.in' => 'Status tidak valid.',
        ]);

        $previousStatus = $aspiration->status;
        $newStatus = $validated['status'];

        // Update status on aspiration
        $aspiration->status = $newStatus;
        $aspiration->save();

        // Record status history
        AspirationStatusHistory::create([
            'aspiration_id' => $aspiration->id,
            'user_id' => Auth::id(),
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'notes' => $validated['notes'] ?? ('Status diubah dari ' . $previousStatus . ' menjadi ' . $newStatus . ' oleh Admin.'),
        ]);

        // Add feedback if provided
        if (!empty($validated['feedback'])) {
            Feedback::create([
                'aspiration_id' => $aspiration->id,
                'user_id' => Auth::id(),
                'response' => $validated['feedback'],
            ]);
        }

        return redirect()->route('admin.aspirasi.show', $aspiration->id)
            ->with('success', 'Status pengaduan berhasil diperbarui menjadi "' . $newStatus . '".');
    }

    /**
     * Store a feedback response for the aspiration.
     */
    public function storeFeedback(Request $request, string $id)
    {
        $aspiration = Aspiration::findOrFail($id);

        $validated = $request->validate([
            'response' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'response.required' => 'Tanggapan / umpan balik tidak boleh kosong.',
            'response.min' => 'Tanggapan minimal 3 karakter.',
        ]);

        Feedback::create([
            'aspiration_id' => $aspiration->id,
            'user_id' => Auth::id(),
            'response' => $validated['response'],
        ]);

        return redirect()->route('admin.aspirasi.show', $aspiration->id)
            ->with('success', 'Umpan balik berhasil dikirimkan kepada siswa.');
    }

    /**
     * Remove the aspiration from storage.
     */
    public function destroy(string $id)
    {
        $aspiration = Aspiration::findOrFail($id);

        // Delete photo if exists
        if ($aspiration->photo) {
            Storage::disk('public')->delete($aspiration->photo);
        }

        $ticket = $aspiration->ticket_number;
        $aspiration->delete();

        return redirect()->route('admin.aspirasi.index')
            ->with('success', 'Pengaduan ' . $ticket . ' berhasil dihapus dari sistem.');
    }
}

