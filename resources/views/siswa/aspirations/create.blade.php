@extends('layouts.app')

@section('title', 'Form Pengaduan Sarana')
@section('page-title', 'Buat Pengaduan Fasilitas Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card sc-card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-pencil-fill"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Formulir Aspirasi Sarana dan Prasarana</h6>
                        <small class="text-muted">Isi informasi laporan kendala atau kerusakan sarana sekolah</small>
                    </div>
                </div>
                <a href="{{ route('siswa.aspirasi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('siswa.aspirasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Judul Pengaduan -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold text-dark">
                            Judul Pengaduan <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="Contoh: AC Ruang Kelas XII RPL 1 Mati dan Meneteskan Air" 
                               required 
                               autofocus>
                        <div class="form-text small">Tuliskan ringkasan inti permasalahan secara singkat dan jelas (5 - 150 karakter).</div>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Kategori Sarana -->
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label fw-bold text-dark">
                                Kategori Sarana & Prasarana <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori Sarana --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Pilih jenis fasilitas yang mengalami kendala.</div>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lokasi Fasilitas -->
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label fw-bold text-dark">
                                Lokasi Fasilitas <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="location" 
                                   id="location" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   value="{{ old('location') }}" 
                                   placeholder="Contoh: Gedung B Lantai 2, R. Kelas XII RPL 1" 
                                   required>
                            <div class="form-text small">Sebutkan gedung, lantai, atau ruangan secara spesifik.</div>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi Pengaduan -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold text-dark">
                            Deskripsi Lengkap Pengaduan <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Jelaskan secara rinci kronologi, tingkat kerusakan, atau dampak yang dirasakan siswa..." 
                                  required>{{ old('description') }}</textarea>
                        <div class="form-text small">Minimal 10 karakter. Jelaskan kendala selengkap mungkin untuk memudahkan tim teknisi.</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload Foto Bukti (Opsional) -->
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label for="photoInput" class="form-label fw-bold text-dark d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-camera me-1 text-primary"></i> Upload Foto Bukti Kerusakan <span class="text-muted fw-normal">(Opsional)</span></span>
                            <span class="badge bg-secondary-subtle text-secondary">Maks. 3 MB (JPG/PNG/WEBP)</span>
                        </label>
                        <input type="file" 
                               name="photo" 
                               id="photoInput" 
                               class="form-control @error('photo') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg,image/webp">
                        <div class="form-text small">Foto kondisi fasilitas yang rusak akan sangat membantu mempercepat proses verifikasi.</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Live Preview Box -->
                        <div id="previewContainer" class="mt-3 d-none">
                            <span class="small fw-semibold text-muted d-block mb-1">Pratinjau Foto yang Dipilih:</span>
                            <img id="photoPreview" src="" alt="Pratinjau" class="img-thumbnail" style="max-height: 200px; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Notice & Submit Button -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle text-primary me-1"></i> Data pelapor: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->kelas ?? 'Siswa' }})
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                            <i class="bi bi-send-fill me-1"></i> Kirim Pengaduan Sarana
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guidance Card -->
        <div class="card sc-card mt-4 bg-light border-0">
            <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-lightbulb-fill text-warning me-1"></i> Tips Pengajuan Pengaduan yang Efektif:</h6>
                <ul class="text-muted small mb-0 ps-3">
                    <li>Gunakan bahasa yang sopan, jelas, dan tidak menyinggung pihak manapun.</li>
                    <li>Sertakan informasi lokasi sedetail mungkin agar petugas sarpras dapat langsung menemukan titik kerusakan.</li>
                    <li>Lampirkan foto bukti kondisi nyata untuk mempermudah identifikasi kebutuhan alat/material perbaikan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

