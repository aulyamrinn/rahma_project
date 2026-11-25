@extends('layouts.auth')

@section('title', 'Masuk ke Sistem')

@section('content')
<form action="{{ route('login') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="login" class="form-label fw-semibold small text-muted">Email atau NISN</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person text-secondary"></i></span>
            <input type="text" 
                   name="login" 
                   id="login" 
                   class="form-control @error('login') is-invalid @enderror" 
                   value="{{ old('login') }}" 
                   placeholder="Masukkan email atau NISN..." 
                   required 
                   autofocus>
        </div>
        @error('login')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-semibold small text-muted">Kata Sandi (Password)</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-lock text-secondary"></i></span>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Masukkan kata sandi..." 
                   required>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label small text-muted" for="remember">
                Ingat Sesi Saya
            </label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm mb-3" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
    </button>

    <div class="text-center small text-muted mb-4">
        Belum memiliki akun siswa? 
        <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Daftar Akun Baru</a>
    </div>

    <!-- Quick Fill Demo Accounts for UKK Reviewers -->
    <div class="p-3 bg-light rounded-3 border">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="fw-bold text-dark small"><i class="bi bi-key-fill text-warning me-1"></i> Akun Pengujian (Demo UKK):</span>
        </div>
        <div class="d-grid gap-2">
            <button type="button" 
                    class="btn btn-outline-dark btn-sm text-start d-flex justify-content-between align-items-center"
                    onclick="quickFillLogin('admin@schoolcare.sch.id', 'password')">
                <span><i class="bi bi-shield-lock me-1 text-danger"></i> <strong>Admin:</strong> admin@schoolcare.sch.id</span>
                <span class="badge bg-secondary">Isi Otomatis</span>
            </button>
            <button type="button" 
                    class="btn btn-outline-primary btn-sm text-start d-flex justify-content-between align-items-center"
                    onclick="quickFillLogin('ahmad@siswa.schoolcare.sch.id', 'password')">
                <span><i class="bi bi-person me-1 text-primary"></i> <strong>Siswa:</strong> ahmad@siswa.schoolcare.sch.id</span>
                <span class="badge bg-secondary">Isi Otomatis</span>
            </button>
        </div>
        <div class="text-center mt-2 text-muted" style="font-size: 0.75rem;">
            Default Password: <code>password</code>
        </div>
    </div>
</form>
@endsection

