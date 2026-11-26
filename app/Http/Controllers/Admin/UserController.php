<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users (admin & siswa) with search and role filter.
     */
    public function index(Request $request)
    {
        $query = User::query()->latest();

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('kelas', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('role') && in_array($request->input('role'), ['admin', 'siswa'], true)) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->paginate(10)->withQueryString();

        $counts = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'siswa' => User::where('role', 'siswa')->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        $isSiswa = $validated['role'] === 'siswa';

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
            'nisn' => $isSiswa ? $validated['nisn'] : null,
            'kelas' => $isSiswa ? $validated['kelas'] : null,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna "' . $user->name . '" (' . strtoupper($user->role) . ') berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validated = $this->validateUser($request, $user);

        $isSiswa = $validated['role'] === 'siswa';

        $data = [
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
            'nisn' => $isSiswa ? $validated['nisn'] : null,
            'kelas' => $isSiswa ? $validated['kelas'] : null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna "' . $user->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.pengguna.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan untuk login.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna "' . $name . '" berhasil dihapus dari sistem.');
    }

    /**
     * Shared validation rules for creating and updating users.
     */
    private function validateUser(Request $request, ?User $user = null): array
    {
        $isUpdate = $user !== null;
        $isSiswa = $request->input('role') === 'siswa';

        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => ['required', 'in:admin,siswa'],
            'nisn' => [
                Rule::requiredIf($isSiswa),
                'nullable',
                'numeric',
                'digits_between:5,15',
                Rule::unique('users', 'nisn')->ignore($user?->id),
            ],
            'kelas' => [
                Rule::requiredIf($isSiswa),
                'nullable',
                'string',
                'max:50',
            ],
            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'nisn.required' => 'NISN wajib diisi untuk pengguna dengan role Siswa.',
            'nisn.numeric' => 'NISN harus berupa angka.',
            'nisn.digits_between' => 'NISN harus terdiri dari 5 sampai 15 digit.',
            'nisn.unique' => 'NISN ini sudah terdaftar di sistem.',
            'kelas.required' => 'Kelas / Jurusan wajib diisi untuk pengguna dengan role Siswa.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);
    }
}
