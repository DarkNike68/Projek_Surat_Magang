<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Surat')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .sidebar { width: 260px; position: fixed; top: 0; left: 0; height: 100vh; z-index: 1000; }
        .sidebar .nav-link.active { background-color: #495057; color: white; }
        .content-wrapper { margin-left: 260px; width: calc(100% - 260px); display: flex; flex-direction: column; min-height: 100vh; }
        .top-navbar { background-color: white; border-bottom: 1px solid #e7e7e7; padding: 0.75rem 1.5rem; display: flex; justify-content: flex-end; align-items: center; }
        .main-content { padding: 24px; flex-grow: 1; background-color: #f4f7f6; }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="fas fa-envelope-open-text me-2"></i>
            <span class="fs-4">Projek Surat</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>

            @can('view-admin-menu')
            <li class="nav-item">
                <a href="#submenuKonfigurasi" data-bs-toggle="collapse" class="nav-link text-white d-flex justify-content-between align-items-center {{ request()->routeIs('admin.letter-codes.*') ? 'active' : '' }}">
                    <span><i class="fas fa-cogs me-2"></i> Konfigurasi</span>
                    <i class="fas fa-chevron-down fa-xs"></i>
                </a>
                <ul class="collapse nav flex-column ms-3 {{ request()->routeIs('admin.letter-codes.*') ? 'show' : '' }}" id="submenuKonfigurasi">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('admin.letter-codes.jenis-surat.index') ? 'active' : '' }}" href="{{ route('admin.letter-codes.jenis-surat.index') }}">
                            <i class="fas fa-file-alt fa-xs me-2"></i> Kode Jenis Surat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.letter-codes.jabatan.index') }}" class="nav-link text-white {{ request()->routeIs('admin.letter-codes.jabatan.index') ? 'active' : '' }}">
                            <i class="fas fa-user-tie fa-xs me-2"></i> Kode Jabatan
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.arsip.index') }}" class="nav-link text-white {{ request()->routeIs('admin.arsip.index') ? 'active' : '' }}">
                    <i class="fas fa-archive me-2"></i>
                    Manajemen Arsip
                </a>
            </li>
            <!-- <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open me-2"></i> Kategori Dokumen
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.permissions.index') }}" class="nav-link text-white {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield me-2"></i> Manajemen Izin Akses
                </a>
            </li> -->
            <!-- <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog me-2"></i> Manajemen User
                </a>
            </li> -->
            @endcan
            
            <li>
                <a href="{{ route('surat.step1.show') }}" class="nav-link text-white {{ request()->routeIs('surat.*') ? 'active' : '' }}">
                    <i class="fas fa-plus-square me-2"></i> Buat Surat
                </a>
            </li>
            <li>
                <a href="{{ route('riwayat.index') }}" class="nav-link text-white {{ request()->routeIs('riwayat.index') ? 'active' : '' }}">
                    <i class="fas fa-history me-2"></i> Riwayat Surat
                </a>
            </li>
        </ul>
        <hr>
    </nav>

    <div class="content-wrapper">
        <nav class="top-navbar">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2 fs-4"></i>
                    <strong>{{ Auth::user()->full_name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="main-content">
            <h1 class="mb-4">@yield('title')</h1>
            @yield('content')
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') 
</body>
</html>