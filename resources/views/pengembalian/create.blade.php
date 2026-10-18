@extends('layouts.app')
@section('title', 'Proses Pengembalian')

@section('content')
<div class="page-head">
    <div>
        <h1>Proses Pengembalian</h1>
        <p>Catat kondisi buku dan hitung denda</p>
    </div>
    <a href="{{ route('pengembalian.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<form method="POST" action="{{ route('pengembalian.store', $peminjaman) }}"
      data-confirm-title="Simpan pengembalian?"
      data-confirm="Stok buku akan diperbarui dan denda (jika ada) akan dicatat.">
    @csrf
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Informasi Peminjaman</div>
                <div class="card-body">
                    <dl class="info-list mb-0">
                        <dt>Anggota</dt>
                        <dd>{{ $peminjaman->anggota->nama }} <span class="text-muted">({{ $peminjaman->anggota->nis_nip }})</span></dd>
                        <dt>Tanggal Pinjam</dt>
                        <dd>{{ $peminjaman->tgl_pinjam->format('d/m/Y') }}</dd>
                        <dt>Jatuh Tempo</dt>
                        <dd>{{ $peminjaman->tgl_jatuh_tempo->format('d/m/Y') }}</dd>
                        <dt>Tanggal Kembali</dt>
                        <dd class="mb-0">{{ now()->format('d/m/Y') }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Perhitungan Denda</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Keterlambatan</span><span>{{ $hariTelat }} hari</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Jumlah buku</span><span>{{ $totalBuku }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tarif / buku / hari</span><span>Rp {{ number_format($tarif, 0, ',', '.') }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-semibold fs-6">
                        <span>Total Denda</span>
                        <span class="{{ $denda > 0 ? 'text-danger' : '' }}">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Kondisi Buku Dikembalikan</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Buku</th><th>Jml</th><th>Kondisi Pinjam</th><th>Kondisi Kembali</th><th>Keterangan</th></tr></thead>
                            <tbody>
                                @foreach ($peminjaman->details as $d)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $d->buku->judul }}</div>
                                            <div class="small text-muted">{{ $d->buku->kode_buku }}</div>
                                        </td>
                                        <td>{{ $d->jumlah_pinjam }}</td>
                                        <td>{{ ucfirst($d->kondisi_pinjam) }}</td>
                                        <td style="min-width:130px">
                                            <select name="detail[{{ $d->id_detail_peminjaman }}][kondisi]" class="form-select form-select-sm">
                                                <option value="baik">Baik</option>
                                                <option value="rusak">Rusak</option>
                                                <option value="hilang">Hilang</option>
                                            </select>
                                        </td>
                                        <td style="min-width:160px">
                                            <input type="text" name="detail[{{ $d->id_detail_peminjaman }}][keterangan]" class="form-control form-control-sm" placeholder="Opsional">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="form-text mt-2">Buku berstatus <b>hilang</b> tidak akan menambah stok.</div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-primary px-4">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Pengembalian
                </button>
            </div>
        </div>
    </div>
</form>
@endsection