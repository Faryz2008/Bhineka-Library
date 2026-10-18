@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="page-head">
    <div>
        <h1>Riwayat Transaksi</h1>
        <p>Catatan peminjaman dan pengembalian buku</p>
    </div>
</div>

<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'peminjaman' ? 'active' : '' }}" href="{{ route('riwayat.index', ['tab' => 'peminjaman']) }}">Peminjaman</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'pengembalian' ? 'active' : '' }}" href="{{ route('riwayat.index', ['tab' => 'pengembalian']) }}">Pengembalian</a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3 align-items-end">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-md-3">
                <label class="form-label">Dari tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            @if ($tab === 'peminjaman')
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>#</th><th>Anggota</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Jml Buku</th><th>Petugas</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $p)
                            <tr>
                                <td class="text-muted">{{ $p->id_peminjaman }}</td>
                                <td class="fw-medium">{{ $p->anggota->nama }}</td>
                                <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tgl_jatuh_tempo->format('d/m/Y') }}</td>
                                <td>{{ $p->total_buku }}</td>
                                <td>{{ $p->user->nama }}</td>
                                <td>@include('partials.status', ['p' => $p])</td>
                                <td class="text-end">
                                    <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="table align-middle mb-0">
                    <thead>
                        <tr><th>#</th><th>Anggota</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Denda</th><th>Petugas</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $r)
                            <tr>
                                <td class="text-muted">{{ $r->id_pengembalian }}</td>
                                <td class="fw-medium">{{ $r->peminjaman->anggota->nama }}</td>
                                <td>{{ $r->peminjaman->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $r->tgl_kembali->format('d/m/Y') }}</td>
                                <td>
                                    @if ($r->total_denda > 0)
                                        <span class="chip chip-red">Rp {{ number_format($r->total_denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="chip chip-green">Tanpa denda</span>
                                    @endif
                                </td>
                                <td>{{ $r->user->nama }}</td>
                                <td class="text-end">
                                    <a href="{{ route('peminjaman.show', $r->id_peminjaman) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>

        <div class="mt-3">{{ $data->links() }}</div>
    </div>
</div>
@endsection