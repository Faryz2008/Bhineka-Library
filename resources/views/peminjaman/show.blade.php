@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
<div class="page-head">
    <div>
        <h1>Detail Peminjaman #{{ $peminjaman->id_peminjaman }}</h1>
        <p>@include('partials.status', ['p' => $peminjaman])</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        @if ($peminjaman->status === 'dipinjam')
            <a href="{{ route('pengembalian.create', $peminjaman) }}" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-down-left me-1"></i> Proses Pengembalian
            </a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Informasi</div>
            <div class="card-body">
                <dl class="info-list mb-0">
                    <dt>Anggota</dt>
                    <dd>{{ $peminjaman->anggota->nama }} <span class="text-muted">({{ $peminjaman->anggota->nis_nip }})</span></dd>
                    <dt>Tanggal Pinjam</dt>
                    <dd>{{ $peminjaman->tgl_pinjam->format('d/m/Y') }}</dd>
                    <dt>Jatuh Tempo</dt>
                    <dd>{{ $peminjaman->tgl_jatuh_tempo->format('d/m/Y') }}</dd>
                    <dt>Dicatat oleh</dt>
                    <dd>{{ $peminjaman->user->nama }}</dd>
                    @if ($peminjaman->pengembalian)
                        <dt>Tanggal Kembali</dt>
                        <dd>{{ $peminjaman->pengembalian->tgl_kembali->format('d/m/Y') }}</dd>
                        <dt>Total Denda</dt>
                        <dd>Rp {{ number_format($peminjaman->pengembalian->total_denda, 0, ',', '.') }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Buku yang Dipinjam</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th>Kode</th><th>Judul</th><th>Jumlah</th><th>Kondisi</th></tr></thead>
                        <tbody>
                            @foreach ($peminjaman->details as $d)
                                <tr>
                                    <td class="text-muted">{{ $d->buku->kode_buku }}</td>
                                    <td class="fw-medium">{{ $d->buku->judul }}</td>
                                    <td>{{ $d->jumlah_pinjam }}</td>
                                    <td>{{ ucfirst($d->kondisi_pinjam) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection