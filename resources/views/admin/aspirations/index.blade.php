@extends('layouts.app')

@section('title', 'Manajemen Aspirasi Siswa')
@section('page-title', 'Manajemen Pengaduan Sarana')

@section('content')
<!-- Status Quick Filter Buttons -->
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('admin.aspirasi.index', request()->except('status', 'page')) }}" 
       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">
        Semua ({{ $counts['total'] }})
    </a>
    <a href="{{ route('admin.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Menunggu'])) }}" 
       class="btn btn-sm {{ request('status') === 'Menunggu' ? 'btn-warning' : 'btn-outline-warning text-dark bg-white' }} rounded-pill px-3">
        Menunggu ({{ $counts['menunggu'] }})
    </a>
    <a href="{{ route('admin.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Diproses'])) }}" 
       class="btn btn-sm {{ request('status') === 'Diproses' ? 'btn-primary' : 'btn-outline-primary bg-white' }} rounded-pill px-3">
        Diproses ({{ $counts['diproses'] }})
    </a>
    <a href="{{ route('admin.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Selesai'])) }}" 
       class="btn btn-sm {{ request('status') === 'Selesai' ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-3">
        Selesai ({{ $counts['selesai'] }})
    </a>
    <a href="{{ route('admin.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Ditolak'])) }}" 
       class="btn btn-sm {{ request('status') === 'Ditolak' ? 'btn-danger' : 'btn-outline-danger bg-white' }} rounded-pill px-3">
        Ditolak ({{ $counts['ditolak'] }})
    </a>
</div>

<!-- Search & Advanced Filter Card -->
<div class="card sc-card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.aspirasi.index') }}" method="GET" class="row g-2 align-items-end">
            <!-- Keyword Search -->
            <div class="col-md-3">
                <label for="q" class="form-label small fw-semibold text-muted">Pencarian Kata Kunci</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" 
                           name="q" 
                           id="q" 
                           class="form-control form-control-sm" 
                           value="{{ request('q') }}" 
                           placeholder="Tiket, judul, nama siswa...">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="col-md-2">
                <label for="category_id" class="form-label small fw-semibold text-muted">Kategori Fasilitas</label>
                <select name="category_id" id="category_id" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Student Filter -->
            <div class="col-md-2">
                <label for="user_id" class="form-label small fw-semibold text-muted">Filter Siswa</label>
                <select name="user_id" id="user_id" class="form-select form-select-sm">
                    <option value="">-- Semua Siswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ request('user_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->name }} ({{ $st->kelas ?? 'Siswa' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filter -->
            <div class="col-md-2">
                <label for="date" class="form-label small fw-semibold text-muted">Filter Tanggal</label>
                <input type="date" 
                       name="date" 
                       id="date" 
                       class="form-control form-control-sm" 
                       value="{{ request('date') }}">
            </div>

            <!-- Month Filter -->
            <div class="col-md-2">
                <label for="month" class="form-label small fw-semibold text-muted">Filter Bulan</label>
                <input type="month" 
                       name="month" 
                       id="month" 
                       class="form-control form-control-sm" 
                       value="{{ request('month') }}">
            </div>

            <!-- Action Buttons -->
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100" title="Terapkan Filter" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                    <i class="bi bi-filter"></i>
                </button>
                <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-light btn-sm border" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Main Table Card -->
<div class="card sc-card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <div class="fw-bold text-dark">
            <i class="bi bi-table text-primary me-1"></i> Data Pengaduan Sarana Sekolah ({{ $aspirations->total() }})
        </div>
        <small class="text-muted">Halaman {{ $aspirations->currentPage() }} dari {{ $aspirations->lastPage() }}</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sc table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 120px;">No. Tiket</th>
                        <th style="width: 180px;">Pelapor (Siswa)</th>
                        <th>Kategori & Lokasi</th>
                        <th>Judul Pengaduan</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-end" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aspirations as $aspiration)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">{{ $aspiration->ticket_number }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $aspiration->user->name }}</div>
                                <small class="text-muted">NISN: {{ $aspiration->user->nisn ?? '-' }} | {{ $aspiration->user->kelas ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary mb-1">
                                    <i class="bi {{ $aspiration->category->icon ?? 'bi-tag' }} me-1"></i> {{ $aspiration->category->name }}
                                </span>
                                <div class="small text-muted text-truncate" style="max-width: 180px;" title="{{ $aspiration->location }}">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i> {{ $aspiration->location }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.aspirasi.show', $aspiration->id) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                                    {{ $aspiration->title }}
                                </a>
                                <div class="small text-muted text-truncate" style="max-width: 280px;" title="{{ $aspiration->description }}">
                                    {{ Str::limit($aspiration->description, 60) }}
                                </div>
                            </td>
                            <td>
                                @if($aspiration->photo)
                                    <a href="{{ $aspiration->photo_url }}" target="_blank" class="badge bg-primary-subtle text-primary text-decoration-none">
                                        <i class="bi bi-image me-1"></i> Foto
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status badge-status-{{ $aspiration->status }}">
                                    <i class="bi {{ $aspiration->getStatusIcon() }}"></i> {{ $aspiration->status }}
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark">{{ $aspiration->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $aspiration->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.aspirasi.show', $aspiration->id) }}" class="btn btn-outline-primary" title="Tindak Lanjut & Detail">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.aspirasi.destroy', $aspiration->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger btn-confirm-delete" 
                                                data-confirm="Apakah Anda yakin ingin menghapus pengaduan nomor {{ $aspiration->ticket_number }}?"
                                                title="Hapus Data">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 d-block text-secondary mb-2"></i>
                                <h5>Tidak Ada Data Pengaduan Ditemukan</h5>
                                <p class="small mb-0">Coba ubah kriteria pencarian atau atur ulang filter Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($aspirations->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-end">
            {{ $aspirations->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

