@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
<div class="page-head">
    <div>
        <h1>Peminjaman</h1>
        <p>Daftar transaksi peminjaman buku</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Peminjaman Baru</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIS/NIP anggota...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="dipinjam" @selected(request('status') === 'dipinjam')>Dipinjam</option>
                    <option value="terlambat" @selected(request('status') === 'terlambat')>Terlambat</option>
                    <option value="dikembalikan" @selected(request('status') === 'dikembalikan')>Dikembalikan</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Anggota</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Jml Buku</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $p)
                        <tr>
                            <td class="text-muted">{{ $p->id_peminjaman }}</td>
                            <td>
                                <div class="fw-medium">{{ $p->anggota->nama }}</div>
                                <div class="small text-muted">{{ $p->anggota->nis_nip }}</div>
                            </td>
                            <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tgl_jatuh_tempo->format('d/m/Y') }}</td>
                            <td>{{ $p->total_buku }}</td>
                            <td>@include('partials.status', ['p' => $p])</td>
                            <td class="text-end">
                                <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada transaksi peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $peminjaman->links() }}</div>
    </div>
</div>
@endsection