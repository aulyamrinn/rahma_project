<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SchoolCare Sarpras</title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom SchoolCare CSS -->
    <link rel="stylesheet" href="{{ asset('css/schoolcare.css') }}">
    @stack('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="app-sidebar" id="appSidebar">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="sidebar-brand-text">
                <h5>SchoolCare</h5>
                <small>Sarpras Sekolah</small>
            </div>
        </a>

        <!-- Current User Profile Card -->
        <div class="sidebar-user text-white">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="rounded-circle bg-primary bg-opacity-25 p-2 text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="fw-semibold text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                    <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-info text-dark' }} px-2 py-0" style="font-size: 0.7rem;">
                        {{ Auth::user()->isAdmin() ? 'ADMINISTRATOR' : 'SISWA' }}
                    </span>
                </div>
            </div>
            @if(Auth::user()->isSiswa() && Auth::user()->kelas)
                <div class="text-white-50 small mt-1" style="font-size: 0.75rem;">
                    <i class="bi bi-mortarboard me-1"></i> {{ Auth::user()->kelas }} (NISN: {{ Auth::user()->nisn }})
                </div>
            @endif
        </div>

        <nav class="sidebar-menu">
            @if(Auth::user()->isAdmin())
                <div class="sidebar-header">Menu Utama Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link-sc {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.aspirasi.index') }}" class="nav-link-sc {{ request()->routeIs('admin.aspirasi.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-data"></i>
                    <span>Kelola Pengaduan</span>
                </a>
            @else
                <div class="sidebar-header">Menu Siswa</div>
                <a href="{{ route('siswa.dashboard') }}" class="nav-link-sc {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('siswa.aspirasi.create') }}" class="nav-link-sc {{ request()->routeIs('siswa.aspirasi.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Buat Pengaduan</span>
                </a>
                <a href="{{ route('siswa.aspirasi.index') }}" class="nav-link-sc {{ request()->routeIs('siswa.aspirasi.index') || request()->routeIs('siswa.aspirasi.show') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Pengaduan Saya</span>
                </a>
            @endif

            <div class="sidebar-header mt-3">Akun & Sesi</div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link-sc text-danger-emphasis">
                <i class="bi bi-box-arrow-right text-danger"></i>
                <span class="text-white">Keluar (Logout)</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="app-main">
        <!-- Top Navbar -->
        <header class="app-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none border" id="sidebarToggle" type="button" aria-label="Toggle Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-sm-block">
                    <h6 class="mb-0 fw-bold text-dark">@yield('page-title', 'SchoolCare')</h6>
                    <small class="text-muted">Sistem Pengaduan Sarana dan Prasarana Sekolah</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-inline text-muted small">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <div class="vr d-none d-md-block" style="height: 24px;"></div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-6 text-primary"></i>
                        <span class="fw-medium small d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                        <li><span class="dropdown-item-text small text-muted">Role: <strong>{{ strtoupper(Auth::user()->role) }}</strong></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Notification Messages -->
        <main class="flex-grow-1 p-3 p-md-4">
            <div class="container-fluid px-0">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                        <i class="bi bi-exclamation-circle-fill fs-5 me-2 text-warning"></i>
                        <div>{{ session('warning') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill fs-5 me-2 text-info"></i>
                        <div>{{ session('info') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-2"></i> Terjadi kesalahan pada isian formulir:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Content Body -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
            <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <div>&copy; {{ date('Y') }} <strong>SchoolCare</strong> - Aplikasi Pengaduan Sarana Sekolah (UKK RPL)</div>
                <div class="text-secondary">Versi 1.0 &bull; Laravel 12 &bull; Bootstrap 5</div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom SchoolCare JS -->
    <script src="{{ asset('js/schoolcare.js') }}"></script>
    @stack('scripts')
</body>
</html>

