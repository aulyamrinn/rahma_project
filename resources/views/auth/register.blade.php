@extends('layouts.auth')

@section('title', 'Pendaftaran Siswa Baru')

@section('content')
<form action="{{ route('register') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label fw-semibold small text-muted">Nama Lengkap Siswa</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person text-secondary"></i></span>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" 
                   placeholder="Contoh: Muhammad Rizki" 
                   required 
                   autofocus>
        </div>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nisn" class="form-label fw-semibold small text-muted">NISN (Nomor Induk)</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-card-heading text-secondary"></i></span>
                <input type="text" 
                       name="nisn" 
                       id="nisn" 
                       class="form-control @error('nisn') is-invalid @enderror" 
                       value="{{ old('nisn') }}" 
                       placeholder="10 digit NISN" 
                       required>
            </div>
            @error('nisn')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="kelas" class="form-label fw-semibold small text-muted">Kelas / Jurusan</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-mortarboard text-secondary"></i></span>
                <input type="text" 
                       name="kelas" 
                       id="kelas" 
                       class="form-control @error('kelas') is-invalid @enderror" 
                       value="{{ old('kelas') }}" 
                       placeholder="Contoh: XII RPL 2" 
                       required>
            </div>
            @error('kelas')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-3">
            <label for="email" class="form-label fw-semibold small text-muted">Alamat Email Aktif</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope text-secondary"></i></span>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="email@sekolah.sch.id" 
                       required>
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-5 mb-3">
            <label for="phone" class="form-label fw-semibold small text-muted">No. WhatsApp <span class="text-muted fw-normal">(Opsional)</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-secondary"></i></span>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone') }}" 
                       placeholder="0812xxxx">
            </div>
            @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label fw-semibold small text-muted">Kata Sandi (Password)</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock text-secondary"></i></span>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Min. 6 karakter" 
                       required>
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-4">
            <label for="password_confirmation" class="form-label fw-semibold small text-muted">Konfirmasi Sandi</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock-fill text-secondary"></i></span>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       class="form-control" 
                       placeholder="Ulangi sandi" 
                       required>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm mb-3" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
        <i class="bi bi-check-circle me-1"></i> Buat Akun Siswa
    </button>

    <div class="text-center small text-muted">
        Sudah memiliki akun? 
        <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Masuk ke Sistem</a>
    </div>
</form>
@endsection

