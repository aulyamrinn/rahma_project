@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna (Admin & Siswa)')

@section('content')
<!-- Header & Add User Action -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.pengguna.index', request()->except('role', 'page')) }}"
           class="btn btn-sm {{ !request('role') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">
            Semua ({{ $counts['total'] }})
        </a>
        <a href="{{ route('admin.pengguna.index', array_merge(request()->except('page'), ['role' => 'admin'])) }}"
           class="btn btn-sm {{ request('role') === 'admin' ? 'btn-danger' : 'btn-outline-danger bg-white' }} rounded-pill px-3">
            Admin ({{ $counts['admin'] }})
        </a>
        <a href="{{ route('admin.pengguna.index', array_merge(request()->except('page'), ['role' => 'siswa'])) }}"
           class="btn btn-sm {{ request('role') === 'siswa' ? 'btn-info text-dark' : 'btn-outline-primary bg-white' }} rounded-pill px-3">
            Siswa ({{ $counts['siswa'] }})
        </a>
    </div>

    <a href="{{ route('admin.pengguna.create') }}"
       class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm"
       style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna
    </a>
</div>

<!-- Search Card -->
<div class="card sc-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.pengguna.index') }}" method="GET" class="row g-2 align-items-center">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text"
                           name="q"
                           class="form-control form-control-sm"
                           value="{{ request('q') }}"
                           placeholder="Cari berdasarkan nama, email, NISN, atau kelas...">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-light btn-sm border" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card sc-card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <div class="fw-bold text-dark">
            <i class="bi bi-people-fill text-primary me-1"></i> Data Pengguna ({{ $users->total() }})
        </div>
        <small class="text-muted">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sc table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>NISN & Kelas</th>
                        <th>Terakhir Diperbarui</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $user->name }}
                                    @if($user->id === Auth::id())
                                        <span class="badge bg-success-subtle text-success border ms-1" style="font-size: 0.68rem;">Anda</span>
                                    @endif
                                </div>
                                <small class="text-muted">Dibuat: {{ $user->created_at->translatedFormat('d M Y') }}</small>
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        <i class="bi bi-shield-lock me-1"></i> ADMIN
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                        <i class="bi bi-mortarboard me-1"></i> SISWA
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark small">{{ $user->email }}</span>
                            </td>
                            <td>
                                @if($user->isSiswa())
                                    <div class="small fw-semibold text-dark">{{ $user->kelas ?? '-' }}</div>
                                    <small class="text-muted font-monospace">NISN: {{ $user->nisn ?? '-' }}</small>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($user->updated_at && $user->updated_at->ne($user->created_at))
                                    <div class="small text-dark">{{ $user->updated_at->translatedFormat('d M Y, H:i') }} WIB</div>
                                    <small class="text-primary">{{ $user->updated_at->diffForHumans() }}</small>
                                @else
                                    <div class="small text-muted">Belum pernah diubah</div>
                                    <small class="text-secondary">{{ $user->created_at->translatedFormat('d M Y, H:i') }} WIB</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn btn-outline-primary" title="Edit Pengguna">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-confirm-delete"
                                                    data-confirm="Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Seluruh aspirasi milik pengguna ini juga akan terhapus."
                                                    title="Hapus Pengguna">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block text-secondary mb-2"></i>
                                <h6>Tidak Ada Pengguna Ditemukan</h6>
                                <p class="small mb-0">Coba ubah kata kunci pencarian atau filter role.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-end">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
