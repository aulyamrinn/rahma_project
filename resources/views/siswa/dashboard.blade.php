@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')

@section('content')
<!-- Welcome Banner -->
<div class="card sc-card mb-4 border-0 text-white" style="background: linear-gradient(135deg, var(--sc-navy) 0%, var(--sc-blue) 100%);">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h4 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h4>
            <p class="mb-0 text-white-50">
                Selamat datang di portal SchoolCare. Laporkan kendala sarana kelas atau lingkungan sekolah Anda di sini.
            </p>
        </div>
        <div>
            <a href="{{ route('siswa.aspirasi.create') }}" class="btn btn-light fw-semibold text-primary px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Buat Pengaduan Baru
            </a>
        </div>
    </div>
</div>

<!-- Student Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalAspirations }}</h3>
                <p>Total Aspirasi Saya</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countMenunggu }}</h3>
                <p>Menunggu Verifikasi</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-info-sc">
            <div class="stat-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countDiproses }}</h3>
                <p>Sedang Diproses</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $countSelesai }}</h3>
                <p>Selesai Diperbaiki</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Aspirations List -->
    <div class="col-lg-7">
        <div class="card sc-card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-journal-text text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Pengaduan Terakhir Saya</h6>
                </div>
                <a href="{{ route('siswa.aspirasi.index') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sc table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No. Tiket</th>
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
                                        <span class="badge bg-secondary-subtle text-secondary small mb-1">
                                            <i class="bi {{ $item->category->icon ?? 'bi-tag' }} me-1"></i> {{ $item->category->name }}
                                        </span>
                                        <div class="text-truncate fw-semibold text-dark" style="max-width: 240px;" title="{{ $item->title }}">
                                            {{ $item->title }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-status-{{ $item->status }}">
                                            <i class="bi {{ $item->getStatusIcon() }}"></i> {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('siswa.aspirasi.show', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="bi bi-eye"></i> Pantau
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder2-open fs-2 text-secondary mb-2 d-block"></i>
                                        <p class="mb-2">Anda belum pernah mengajukan pengaduan sarana.</p>
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
        </div>
    </div>

    <!-- Admin Feedbacks Feed -->
    <div class="col-lg-5">
        <div class="card sc-card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-chat-dots-fill text-info fs-5"></i>
                <h6 class="mb-0 fw-bold">Tanggapan Admin Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @forelse($recentFeedbacks as $fb)
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-primary-subtle text-primary font-monospace">{{ $fb->aspiration->ticket_number }}</span>
                                <small class="text-muted">{{ $fb->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="fw-semibold text-dark small mb-1 text-truncate" title="{{ $fb->aspiration->title }}">
                                {{ $fb->aspiration->title }}
                            </div>
                            <p class="small text-secondary mb-2" style="white-space: pre-line;">
                                "{{ Str::limit($fb->response, 120) }}"
                            </p>
                            <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                <small class="text-muted"><i class="bi bi-person-check me-1"></i> Admin Sarpras</small>
                                <a href="{{ route('siswa.aspirasi.show', $fb->aspiration_id) }}" class="small text-primary text-decoration-none fw-semibold">
                                    Lihat Selengkapnya &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted small">
                            <i class="bi bi-bell-slash text-secondary fs-3 d-block mb-2"></i>
                            Belum ada tanggapan baru dari tim admin sarpras.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

