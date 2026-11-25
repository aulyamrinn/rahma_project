@extends('layouts.app')

@section('title', 'Dashboard Administrator')
@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="row g-3 mb-4">
    <!-- Stat 1: Total -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="bi bi-collection"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalAspirations }}</h3>
                <p>Total Aspirasi</p>
            </div>
        </div>
    </div>

    <!-- Stat 2: Menunggu -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countMenunggu }}</h3>
                <p>Menunggu</p>
            </div>
        </div>
    </div>

    <!-- Stat 3: Diproses -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-info-sc">
            <div class="stat-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countDiproses }}</h3>
                <p>Diproses</p>
            </div>
        </div>
    </div>

    <!-- Stat 4: Selesai -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countSelesai }}</h3>
                <p>Selesai</p>
            </div>
        </div>
    </div>

    <!-- Stat 5: Ditolak -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-danger">
            <div class="stat-icon">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countDitolak }}</h3>
                <p>Ditolak</p>
            </div>
        </div>
    </div>

    <!-- Stat 6: Total Siswa -->
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Siswa</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Aspirations Table -->
    <div class="col-lg-8">
        <div class="card sc-card h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Pengaduan Terbaru Masuk</h6>
                </div>
                <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sc table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tiket</th>
                                <th>Siswa</th>
                                <th>Kategori & Judul</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAspirations as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $item->ticket_number }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->user->name }}</div>
                                        <small class="text-muted">{{ $item->user->kelas ?? 'Siswa' }}</small>
                                    </td>
                                    <td>
                                        <div class="badge bg-secondary-subtle text-secondary small mb-1">
                                            <i class="bi {{ $item->category->icon ?? 'bi-tag' }} me-1"></i> {{ $item->category->name }}
                                        </div>
                                        <div class="text-truncate" style="max-width: 240px;" title="{{ $item->title }}">
                                            {{ $item->title }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                        <div style="font-size: 0.72rem;" class="text-secondary">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-status-{{ $item->status }}">
                                            <i class="bi {{ $item->getStatusIcon() }}"></i> {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.aspirasi.show', $item->id) }}" class="btn btn-sm btn-light border" title="Detail & Tanggapi">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block text-secondary mb-2"></i>
                                        Belum ada pengaduan sarana yang masuk ke sistem.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Summary & Monthly Breakdown -->
    <div class="col-lg-4">
        <!-- Category Summary -->
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-pie-chart text-info fs-5"></i>
                <h6 class="mb-0 fw-bold">Ringkasan Kategori Fasilitas</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @foreach($categorySummary as $cat)
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="fw-semibold text-dark">
                                    <i class="bi {{ $cat['icon'] ?? 'bi-folder' }} text-secondary me-1"></i> {{ $cat['name'] }}
                                </span>
                                <span class="text-muted fw-bold">{{ $cat['count'] }} ({{ $cat['percentage'] }}%)</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $cat['percentage'] }}%;" aria-valuenow="{{ $cat['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System Quick Info -->
        <div class="card sc-card bg-light border-0">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-shield-check text-primary me-1"></i> Mode Penilaian UKK</h6>
                <p class="text-muted small mb-3">Sistem beroperasi sesuai standar kompetensi Rekayasa Perangkat Lunak. Seluruh perubahan status langsung tercatat ke tabel <code>aspiration_status_histories</code>.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-sm btn-primary" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                        <i class="bi bi-list-check me-1"></i> Buka Manajemen Aspirasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

