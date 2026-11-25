@extends('layouts.app')

@section('title', 'Detail Pengaduan: ' . $aspiration->ticket_number)
@section('page-title', 'Detail & Perkembangan Pengaduan')

@section('content')
<div class="mb-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <a href="{{ route('siswa.aspirasi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill bg-white">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pengaduan
    </a>

    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">Status Pengaduan:</span>
        <span class="badge-status badge-status-{{ $aspiration->status }}">
            <i class="bi {{ $aspiration->getStatusIcon() }}"></i> {{ $aspiration->status }}
        </span>
    </div>
</div>

<!-- Interactive Progress Stepper -->
<div class="card sc-card mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-1">Tahapan Progres Penanganan Fasilitas</h6>
        <p class="text-muted small mb-3">Ikuti setiap perkembangan laporan pengaduan Anda secara berkala</p>

        @php
            $isRejected = ($aspiration->status === 'Ditolak');
            $isDone = ($aspiration->status === 'Selesai');
            $isProcessing = ($aspiration->status === 'Diproses');
        @endphp

        <div class="progress-stepper">
            <!-- Step 1: Menunggu -->
            <div class="progress-step {{ $isRejected ? 'completed' : ($isDone || $isProcessing ? 'completed' : 'active') }}">
                <div class="step-circle">
                    <i class="bi {{ $isDone || $isProcessing || $isRejected ? 'bi-check-lg' : 'bi-1-circle' }}"></i>
                </div>
                <div class="step-label">Menunggu Verifikasi</div>
                <small class="text-muted d-none d-sm-block" style="font-size: 0.72rem;">Laporan Terkirim</small>
            </div>

            <!-- Step 2: Diproses -->
            <div class="progress-step {{ $isRejected ? '' : ($isDone ? 'completed' : ($isProcessing ? 'active' : '')) }}">
                <div class="step-circle">
                    @if($isDone)
                        <i class="bi bi-check-lg"></i>
                    @elseif($isProcessing)
                        <i class="bi bi-gear-fill"></i>
                    @else
                        2
                    @endif
                </div>
                <div class="step-label">Sedang Diproses</div>
                <small class="text-muted d-none d-sm-block" style="font-size: 0.72rem;">Penanganan Teknisi</small>
            </div>

            <!-- Step 3: Selesai / Ditolak -->
            @if($isRejected)
                <div class="progress-step rejected">
                    <div class="step-circle">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <div class="step-label text-danger">Ditolak</div>
                    <small class="text-danger d-none d-sm-block" style="font-size: 0.72rem;">Tidak Layak/Batal</small>
                </div>
            @else
                <div class="progress-step {{ $isDone ? 'completed' : '' }}">
                    <div class="step-circle">
                        <i class="bi {{ $isDone ? 'bi-check2-circle' : 'bi-3-circle' }}"></i>
                    </div>
                    <div class="step-label">Selesai</div>
                    <small class="text-muted d-none d-sm-block" style="font-size: 0.72rem;">Fasilitas Normal</small>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Complaint Details -->
    <div class="col-lg-8">
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary font-monospace fs-6">{{ $aspiration->ticket_number }}</span>
                    <span class="badge bg-secondary-subtle text-secondary">
                        <i class="bi {{ $aspiration->category->icon ?? 'bi-tag' }} me-1"></i> {{ $aspiration->category->name }}
                    </span>
                </div>
                <small class="text-muted">{{ $aspiration->created_at->translatedFormat('d F Y, H:i') }} WIB</small>
            </div>
            <div class="card-body">
                <h4 class="fw-bold text-dark mb-3">{{ $aspiration->title }}</h4>

                <div class="p-3 bg-light rounded-3 mb-4 border">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Lokasi Fasilitas:</span>
                            <span class="fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $aspiration->location }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Kategori Sarpras:</span>
                            <span class="fw-semibold text-dark"><i class="bi bi-folder-fill text-primary me-1"></i> {{ $aspiration->category->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Pengaduan:</h6>
                    <div class="p-3 bg-white border rounded-3 text-secondary" style="white-space: pre-line; line-height: 1.7;">
                        {{ $aspiration->description }}
                    </div>
                </div>

                <!-- Photo Evidence -->
                <div>
                    <h6 class="fw-bold text-dark mb-2">Foto Bukti Kerusakan:</h6>
                    @if($aspiration->photo)
                        <div class="border rounded-3 p-2 bg-light d-inline-block">
                            <img src="{{ $aspiration->photo_url }}" 
                                 alt="Bukti Pengaduan" 
                                 class="img-fluid rounded" 
                                 style="max-height: 280px; object-fit: contain; cursor: pointer;"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#photoModal">
                            <div class="text-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#photoModal">
                                    <i class="bi bi-zoom-in me-1"></i> Lihat Foto Penuh
                                </button>
                            </div>
                        </div>

                        <!-- Modal for enlarged photo -->
                        <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title fw-bold">Foto Bukti Pengaduan - {{ $aspiration->ticket_number }}</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center p-2 bg-dark">
                                        <img src="{{ $aspiration->photo_url }}" alt="Bukti Foto" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i> Anda tidak menyertakan foto bukti saat mengajukan laporan ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Admin Responses / Feedbacks -->
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-chat-quote-fill text-primary fs-5"></i>
                <h6 class="mb-0 fw-bold">Tanggapan & Umpan Balik Tim Admin Sarpras ({{ $aspiration->feedbacks->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @forelse($aspiration->feedbacks as $fb)
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary">Admin Sarpras</span>
                                    <span class="fw-semibold text-dark small">{{ $fb->user->name }}</span>
                                </div>
                                <span class="text-muted small">{{ $fb->created_at->diffForHumans() }} ({{ $fb->created_at->format('d/m/Y H:i') }})</span>
                            </div>
                            <p class="mb-0 text-dark small" style="white-space: pre-line;">
                                {{ $fb->response }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-hourglass text-secondary fs-3 d-block mb-2"></i>
                            <p class="mb-0">Belum ada tanggapan resmi dari tim admin sarana. Petugas akan meninjau laporan Anda sesegera mungkin.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Timeline & Summary -->
    <div class="col-lg-4">
        <!-- Status History Card -->
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-info fs-5"></i>
                <h6 class="mb-0 fw-bold">Jejak Histori Penanganan</h6>
            </div>
            <div class="card-body">
                <div class="timeline-sc">
                    @forelse($aspiration->statusHistories as $hist)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge-status badge-status-{{ $hist->new_status }}">
                                        {{ $hist->new_status }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.72rem;">{{ $hist->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="mb-1 text-dark small">{{ $hist->notes }}</p>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    Oleh: <strong>{{ $hist->user->name ?? 'Sistem' }}</strong>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada riwayat tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card sc-card bg-light border-0">
            <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-primary me-1"></i> Bantuan & Konfirmasi</h6>
                <p class="small text-muted mb-0">
                    Jika perbaikan sarana telah selesai tetapi kendala masih terulang, silakan buat pengaduan baru dengan melampirkan keterangan tambahan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

