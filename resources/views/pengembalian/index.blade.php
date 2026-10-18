@extends('layouts.app')
@section('title', 'Pengembalian')

@section('content')
<div class="page-head">
    <div>
        <h1>Pengembalian</h1>
        <p>Pilih transaksi yang akan dikembalikan</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIS/NIP anggota...">
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
                                <a href="{{ route('pengembalian.create', $p) }}" class="btn btn-sm btn-primary">Proses</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada buku yang sedang dipinjam.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $peminjaman->links() }}</div>
    </div>
</div>
@endsection