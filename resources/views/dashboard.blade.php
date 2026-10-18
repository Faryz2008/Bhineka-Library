@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-head">
    <div>
        <h1>Dashboard</h1>
        <p>Ringkasan aktivitas perpustakaan</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Peminjaman Baru</a>
</div>

@php
    $stats = [
        ['Total Buku', $totalBuku, 'bi-book'],
        ['Total Anggota', $totalAnggota, 'bi-people'],
        ['Sedang Dipinjam', $sedangDipinjam, 'bi-arrow-left-right'],
        ['Terlambat', $terlambat, 'bi-exclamation-circle'],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach ($stats as [$label, $value, $icon])
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body stat">
                    <div class="icon"><i class="bi {{ $icon }}"></i></div>
                    <div>
                        <h3>{{ $value }}</h3>
                        <span>{{ $label }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">Peminjaman Terbaru</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>Anggota</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $p)
                        <tr>
                            <td>{{ $p->anggota->nama }}</td>
                            <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tgl_jatuh_tempo->format('d/m/Y') }}</td>
                            <td>@include('partials.status', ['p' => $p])</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection