@extends('layouts.app')

@section('title', 'Detail Pengaduan: ' . $aspiration->ticket_number)
@section('page-title', 'Detail & Tindak Lanjut Pengaduan')

@section('content')
<div class="mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
    <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill bg-white">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>

    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">Status Saat Ini:</span>
        <span class="badge-status badge-status-{{ $aspiration->status }}">
            <i class="bi {{ $aspiration->getStatusIcon() }}"></i> {{ $aspiration->status }}
        </span>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Complaint Details & Media -->
    <div class="col-lg-8">
        <!-- Main Info Card -->
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary fs-6 font-monospace">{{ $aspiration->ticket_number }}</span>
                    <span class="badge bg-secondary-subtle text-secondary">
                        <i class="bi {{ $aspiration->category->icon ?? 'bi-tag' }} me-1"></i> {{ $aspiration->category->name }}
                    </span>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-clock me-1"></i> {{ $aspiration->created_at->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </div>
            <div class="card-body">
                <h4 class="fw-bold text-dark mb-3">{{ $aspiration->title }}</h4>

                <div class="p-3 bg-light rounded-3 mb-4 border">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="text-muted small">Lokasi Sarana / Fasilitas:</div>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $aspiration->location }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Kategori Sarpras:</div>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-folder-fill text-primary me-1"></i> {{ $aspiration->category->name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Kerusakan / Kendala:</h6>
                    <div class="p-3 border rounded-3 bg-white" style="white-space: pre-line; line-height: 1.7;">
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
                                 style="max-height: 320px; object-fit: contain; cursor: pointer;"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#photoModal">
                            <div class="text-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#photoModal">
                                    <i class="bi bi-zoom-in me-1"></i> Klik untuk Perbesar Foto
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
                            <i class="bi bi-info-circle me-1"></i> Tidak ada lampiran foto bukti yang diunggah oleh siswa.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feedback & Responses Section -->
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-chat-left-dots text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Tanggapan / Umpan Balik Admin ({{ $aspiration->feedbacks->count() }})</h6>
                </div>
            </div>
            <div class="card-body">
                <!-- Add Feedback Form -->
                <form action="{{ route('admin.aspirasi.feedback', $aspiration->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-2">
                        <label for="response" class="form-label fw-semibold small text-muted">Beri Tanggapan Resmi kepada Siswa:</label>
                        <textarea name="response" 
                                  id="response" 
                                  rows="3" 
                                  class="form-control @error('response') is-invalid @enderror" 
                                  placeholder="Tuliskan pesan konfirmasi, arahan, atau progres perbaikan untuk pelapor..." 
                                  required>{{ old('response') }}</textarea>
                        @error('response')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm px-3" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                            <i class="bi bi-send me-1"></i> Kirim Umpan Balik
                        </button>
                    </div>
                </form>

                <!-- Feedback List -->
                <div class="d-flex flex-column gap-3">
                    @forelse($aspiration->feedbacks as $fb)
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary">Petugas Sarpras</span>
                                    <strong class="text-dark small">{{ $fb->user->name }}</strong>
                                </div>
                                <span class="text-muted small">{{ $fb->created_at->diffForHumans() }} ({{ $fb->created_at->format('d/m/Y H:i') }})</span>
                            </div>
                            <p class="mb-0 text-dark small" style="white-space: pre-line;">{{ $fb->response }}</p>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3 small">
                            <i class="bi bi-chat-square text-secondary d-block fs-4 mb-1"></i>
                            Belum ada umpan balik yang diberikan untuk pengaduan ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Status Change Timeline History -->
        <div class="card sc-card">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-info fs-5"></i>
                <h6 class="mb-0 fw-bold">Histori Perubahan Status Pengaduan</h6>
            </div>
            <div class="card-body">
                <div class="timeline-sc">
                    @forelse($aspiration->statusHistories as $hist)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-1">
                                    <div>
                                        @if($hist->previous_status)
                                            <span class="badge bg-secondary-subtle text-secondary small">{{ $hist->previous_status }}</span>
                                            <i class="bi bi-arrow-right mx-1 small text-muted"></i>
                                        @endif
                                        <span class="badge-status badge-status-{{ $hist->new_status }}">
                                            {{ $hist->new_status }}
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $hist->created_at->translatedFormat('d M Y, H:i') }} WIB</small>
                                </div>
                                <p class="mb-1 text-dark small">{{ $hist->notes }}</p>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    Oleh: <strong>{{ $hist->user->name ?? 'Sistem' }}</strong> 
                                    ({{ $hist->user ? strtoupper($hist->user->role) : 'SYSTEM' }})
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada riwayat status tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Student Info & Update Status Action -->
    <div class="col-lg-4">
        <!-- Update Status Card -->
        <div class="card sc-card mb-4 border-primary">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-gear-fill me-2"></i> Perbarui Status Pengaduan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.aspirasi.update-status', $aspiration->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold small text-muted">Pilih Status Baru:</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Menunggu" {{ $aspiration->status === 'Menunggu' ? 'selected' : '' }}>1. Menunggu (Verifikasi Awal)</option>
                            <option value="Diproses" {{ $aspiration->status === 'Diproses' ? 'selected' : '' }}>2. Diproses (Teknisi Bekerja)</option>
                            <option value="Selesai" {{ $aspiration->status === 'Selesai' ? 'selected' : '' }}>3. Selesai (Perbaikan Tuntas)</option>
                            <option value="Ditolak" {{ $aspiration->status === 'Ditolak' ? 'selected' : '' }}>4. Ditolak (Tidak Layak/Batal)</option>
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold small text-muted">Catatan Log Histori:</label>
                        <input type="text" 
                               name="notes" 
                               id="notes" 
                               class="form-control form-control-sm" 
                               placeholder="Contoh: Teknisi sedang mengganti komponen...">
                        <div class="form-text small">Catatan ini akan muncul pada jejak histori timeline.</div>
                    </div>

                    <div class="mb-3">
                        <label for="feedback" class="form-label fw-semibold small text-muted">Kirim Tanggapan Tambahan (Opsional):</label>
                        <textarea name="feedback" 
                                  id="feedback" 
                                  rows="2" 
                                  class="form-control form-control-sm" 
                                  placeholder="Pesan tambahan yang dibaca siswa..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pembaruan Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Student Profile Card -->
        <div class="card sc-card mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-person-badge text-primary fs-5"></i>
                <h6 class="mb-0 fw-bold">Informasi Siswa (Pelapor)</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-light p-3 border text-primary fs-4">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $aspiration->user->name }}</h6>
                        <span class="badge bg-info-subtle text-info border">Siswa Aktif</span>
                    </div>
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">NISN</span>
                        <strong class="text-dark">{{ $aspiration->user->nisn ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Kelas / Jurusan</span>
                        <strong class="text-dark">{{ $aspiration->user->kelas ?? '-' }}</strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Alamat Email</span>
                        <span class="text-dark">{{ $aspiration->user->email }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">No. WhatsApp</span>
                        <span class="text-dark">{{ $aspiration->user->phone ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Danger Zone Delete -->
        <div class="card sc-card border-danger-subtle bg-danger-subtle bg-opacity-25">
            <div class="card-body p-3">
                <h6 class="fw-bold text-danger mb-1"><i class="bi bi-trash me-1"></i> Hapus Pengaduan</h6>
                <p class="small text-muted mb-2">Hapus pengaduan ini secara permanen dari sistem jika merupakan laporan spam atau duplikat.</p>
                <form action="{{ route('admin.aspirasi.destroy', $aspiration->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-outline-danger btn-sm w-100 btn-confirm-delete"
                            data-confirm="PERINGATAN: Apakah Anda benar-benar yakin ingin menghapus pengaduan nomor {{ $aspiration->ticket_number }} beserta seluruh histori dan responnya?">
                        Hapus Pengaduan Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

