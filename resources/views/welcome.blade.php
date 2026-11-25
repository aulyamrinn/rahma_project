<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolCare - Sistem Pengaduan Sarana dan Prasarana Sekolah</title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom SchoolCare CSS -->
    <link rel="stylesheet" href="{{ asset('css/schoolcare.css') }}">
    <style>
        .hero-section {
            background: linear-gradient(135deg, var(--sc-navy-dark) 0%, var(--sc-navy) 65%, var(--sc-blue) 100%);
            color: #fff;
            padding: 5rem 0 6rem;
            position: relative;
            overflow: hidden;
        }
        .hero-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }
        .feature-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.75rem;
            border: 1px solid var(--sc-card-border);
            transition: all 0.25s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(15, 43, 72, 0.08);
            border-color: var(--sc-blue-light);
        }
        .step-box {
            background: #fff;
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid var(--sc-card-border);
            text-align: center;
            height: 100%;
        }
        .step-number {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--sc-blue);
            color: #fff;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.35);
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: var(--sc-navy-dark);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
                <div class="sidebar-brand-icon" style="width: 36px; height: 36px; font-size: 1.1rem;">
                    <i class="bi bi-shield-check"></i>
                </div>
                <span>SchoolCare</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">
                    <li class="nav-item"><a class="nav-link text-white-50" href="#alur">Alur Pengaduan</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="#kategori">Kategori Fasilitas</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3 rounded-pill" href="{{ route('register') }}" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                            <i class="bi bi-person-plus me-1"></i> Daftar Siswa
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-pattern"></div>
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-center text-lg-start">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-semibold mb-3 shadow-sm">
                        <i class="bi bi-patch-check-fill text-primary me-1"></i> UKK Rekayasa Perangkat Lunak
                    </span>
                    <h1 class="display-5 fw-bold mb-3 lh-sm">Sistem Pengaduan Sarana dan Prasarana Sekolah</h1>
                    <p class="lead text-white-50 mb-4">
                        Laporkan segala kerusakan fasilitas kelas, toilet, laboratorium, internet, dan sarana sekolah secara cepat, transparan, dan dapat dipantau langsung perkembangannya.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 rounded-pill shadow" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                            <i class="bi bi-megaphone me-2"></i> Laporkan Masalah Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            <i class="bi bi-shield-lock me-2"></i> Portal Admin
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="bg-white p-4 rounded-4 shadow-lg text-dark">
                        <div class="d-flex align-items-center gap-3 pb-3 border-bottom mb-3">
                            <div class="p-3 rounded-3 bg-primary bg-opacity-10 text-primary fs-3">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Statistik Penanganan Sarpras</h6>
                                <small class="text-muted">Akurasi & Pemantauan Real-time</small>
                            </div>
                        </div>
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-light">
                                    <h4 class="fw-bold text-primary mb-0">{{ $totalAspirations }}</h4>
                                    <small class="text-muted" style="font-size: 0.72rem;">Total Laporan</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-light">
                                    <h4 class="fw-bold text-warning mb-0">{{ $totalInProcess }}</h4>
                                    <small class="text-muted" style="font-size: 0.72rem;">Diproses</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded-3 bg-light">
                                    <h4 class="fw-bold text-success mb-0">{{ $totalResolved }}</h4>
                                    <small class="text-muted" style="font-size: 0.72rem;">Terselesaikan</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 rounded-3" style="background-color: #f0f7ff; border-left: 4px solid var(--sc-blue);">
                            <p class="mb-0 small text-secondary">
                                <i class="bi bi-info-circle-fill text-primary me-1"></i>
                                Setiap pengaduan yang diajukan langsung mendapatkan <strong>Nomor Tiket</strong> dan diverifikasi oleh tim Sarpras sekolah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- How It Works Section -->
    <section class="py-5" id="alur">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">ALUR PENANGANAN</span>
                <h2 class="fw-bold text-dark">4 Langkah Mudah Pengaduan Fasilitas</h2>
                <p class="text-muted">Proses pengaduan yang terstruktur demi percepatan perbaikan sarana sekolah</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">1</div>
                        <h6 class="fw-bold mb-2">Ajukan Laporan</h6>
                        <p class="text-muted small mb-0">Siswa mengisi formulir pengaduan, memilih kategori, mencantumkan lokasi, dan mengunggah foto bukti.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">2</div>
                        <h6 class="fw-bold mb-2">Verifikasi Admin</h6>
                        <p class="text-muted small mb-0">Tim Admin Sarpras memeriksa validitas laporan dan menentukan jadwal inspeksi teknisi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">3</div>
                        <h6 class="fw-bold mb-2">Proses Perbaikan</h6>
                        <p class="text-muted small mb-0">Status pengaduan diperbarui menjadi "Diproses" dan catatan progres dapat dipantau oleh siswa.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-box">
                        <div class="step-number">4</div>
                        <h6 class="fw-bold mb-2">Selesai & Umpan Balik</h6>
                        <p class="text-muted small mb-0">Perbaikan selesai dikerjakan, admin memberikan tanggapan resmi, dan histori tercatat rapi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="py-5 bg-white border-top border-bottom" id="kategori">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-semibold mb-2">RUANG LINGKUP</span>
                <h2 class="fw-bold text-dark">Kategori Sarana & Prasarana</h2>
                <p class="text-muted">Pilihan kategori lengkap sesuai petunjuk teknis UKK Rekayasa Perangkat Lunak</p>
            </div>

            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="stat-icon mb-3" style="background-color: #e0f2fe; color: #0284c7; width: 48px; height: 48px; border-radius: 12px; font-size: 1.4rem;">
                                <i class="bi {{ $category->icon ?? 'bi-tools' }}"></i>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">{{ $category->name }}</h5>
                            <p class="text-muted small mb-0">{{ $category->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to action -->
    <section class="py-5" style="background: linear-gradient(90deg, var(--sc-navy-dark) 0%, var(--sc-navy) 100%); color: #fff;">
        <div class="container text-center py-4">
            <h3 class="fw-bold mb-3">Temukan Kerusakan Sarana di Sekolah Anda?</h3>
            <p class="text-white-50 mb-4 max-w-600 mx-auto">Jangan biarkan sarana yang rusak mengganggu kegiatan belajar. Laporkan segera melalui SchoolCare!</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 rounded-pill shadow" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                <i class="bi bi-pencil-square me-2"></i> Daftar & Ajukan Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white-50 py-4 border-top border-secondary">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small">
            <div>
                &copy; {{ date('Y') }} <strong>SchoolCare</strong>. Sistem Pengaduan Sarana dan Prasarana Sekolah.
            </div>
            <div>
                UKK Rekayasa Perangkat Lunak &bull; Laravel 12 &bull; PHP 8.2 &bull; Bootstrap 5
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
