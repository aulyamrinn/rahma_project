@extends('layouts.app')

@section('title', 'Daftar Pengaduan Saya')
@section('page-title', 'Pengaduan Sarana Saya')

@section('content')
<!-- Header & Create Action -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-1">Riwayat Pengaduan Sarana</h5>
        <p class="text-muted small mb-0">Pantau seluruh status dan perkembangan pengaduan fasilitas yang telah Anda laporkan.</p>
    </div>
    <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
        <i class="bi bi-plus-circle me-1"></i> Buat Pengaduan Baru
    </a>
</div>

<!-- Status Quick Filter Buttons -->
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('siswa.aspirasi.index', request()->except('status', 'page')) }}" 
       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary bg-white' }} rounded-pill px-3">
        Semua ({{ $counts['total'] }})
    </a>
    <a href="{{ route('siswa.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Menunggu'])) }}" 
       class="btn btn-sm {{ request('status') === 'Menunggu' ? 'btn-warning' : 'btn-outline-warning text-dark bg-white' }} rounded-pill px-3">
        Menunggu ({{ $counts['menunggu'] }})
    </a>
    <a href="{{ route('siswa.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Diproses'])) }}" 
       class="btn btn-sm {{ request('status') === 'Diproses' ? 'btn-primary' : 'btn-outline-primary bg-white' }} rounded-pill px-3">
        Diproses ({{ $counts['diproses'] }})
    </a>
    <a href="{{ route('siswa.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Selesai'])) }}" 
       class="btn btn-sm {{ request('status') === 'Selesai' ? 'btn-success' : 'btn-outline-success bg-white' }} rounded-pill px-3">
        Selesai ({{ $counts['selesai'] }})
    </a>
    <a href="{{ route('siswa.aspirasi.index', array_merge(request()->except('page'), ['status' => 'Ditolak'])) }}" 
       class="btn btn-sm {{ request('status') === 'Ditolak' ? 'btn-danger' : 'btn-outline-danger bg-white' }} rounded-pill px-3">
        Ditolak ({{ $counts['ditolak'] }})
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="card sc-card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('siswa.aspirasi.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" 
                           name="q" 
                           class="form-control form-control-sm" 
                           placeholder="Cari berdasarkan nomor tiket, judul pengaduan, atau lokasi..." 
                           value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- Semua Kategori Fasilitas --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('siswa.aspirasi.index') }}" class="btn btn-light btn-sm border" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Aspirations Table -->
<div class="card sc-card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
        <span class="fw-bold text-dark"><i class="bi bi-list-task text-primary me-1"></i> Data Pengaduan ({{ $aspirations->total() }})</span>
        <span class="text-muted small">Menampilkan {{ $aspirations->count() }} pengaduan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sc table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 140px;">No. Tiket</th>
                        <th>Kategori & Lokasi</th>
                        <th>Judul Pengaduan</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Tanggal Lapor</th>
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
                                <span class="badge bg-secondary-subtle text-secondary mb-1">
                                    <i class="bi {{ $aspiration->category->icon ?? 'bi-tag' }} me-1"></i> {{ $aspiration->category->name }}
                                </span>
                                <div class="small text-muted text-truncate" style="max-width: 160px;" title="{{ $aspiration->location }}">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i> {{ $aspiration->location }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('siswa.aspirasi.show', $aspiration->id) }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $aspiration->title }}
                                </a>
                                <div class="small text-muted text-truncate" style="max-width: 260px;" title="{{ $aspiration->description }}">
                                    {{ Str::limit($aspiration->description, 50) }}
                                </div>
                            </td>
                            <td>
                                @if($aspiration->photo)
                                    <span class="badge bg-primary-subtle text-primary"><i class="bi bi-image me-1"></i> Ada Foto</span>
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
                                <a href="{{ route('siswa.aspirasi.show', $aspiration->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 text-secondary d-block mb-2"></i>
                                <h6>Tidak Ada Pengaduan Ditemukan</h6>
                                <p class="small mb-3">Tidak ditemukan pengaduan yang sesuai dengan kriteria filter.</p>
                                <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-sm btn-primary rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Ajukan Pengaduan Sekarang
                                </a>
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

