<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Bhinneka Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="brand"><i class="bi bi-book-half"></i><span>Bhinneka Library</span></div>

    <nav class="menu">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="menu-label">Master Data</div>
        <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Data Buku
        </a>
        <a href="{{ route('anggota.index') }}" class="{{ request()->routeIs('anggota.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Data Anggota
        </a>
        <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Data Kategori
        </a>

        <div class="menu-label">Transaksi</div>
        <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-up-right"></i> Peminjaman
        </a>
        <a href="{{ route('pengembalian.index') }}" class="{{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-down-left"></i> Pengembalian
        </a>

        <div class="menu-label">Laporan</div>
        <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Riwayat Transaksi
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-3">
        @csrf
        <button type="submit" class="logout"><i class="bi bi-box-arrow-left"></i> Keluar</button>
    </form>
</aside>

<div class="main">
    <header class="topbar">
        <button class="btn btn-light d-lg-none" id="toggleSidebar" type="button"><i class="bi bi-list"></i></button>
        <div class="ms-auto user-chip"><i class="bi bi-person-circle fs-5"></i> {{ auth()->user()->nama }}</div>
    </header>

    <section class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    document.getElementById('toggleSidebar')?.addEventListener('click', () => sidebar.classList.toggle('show'));
    document.querySelector('.content').addEventListener('click', () => sidebar.classList.remove('show'));
</script>
@stack('scripts')
</body>
</html>