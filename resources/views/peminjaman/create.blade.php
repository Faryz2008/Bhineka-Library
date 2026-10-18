@extends('layouts.app')
@section('title', 'Peminjaman Baru')

@section('content')
<div class="page-head">
    <div>
        <h1>Peminjaman Baru</h1>
        <p>Pilih anggota dan buku yang akan dipinjam</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<form method="POST" action="{{ route('peminjaman.store') }}">
    @csrf
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Data Peminjam</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Anggota</label>
                        <input type="text" id="cariAnggota" class="form-control form-control-sm mb-2" placeholder="Cari nama / NIS...">
                        <select name="id_anggota" id="selectAnggota" class="form-select" required>
                            <option value="">Pilih anggota</option>
                            @foreach ($anggota as $a)
                                <option value="{{ $a->id_anggota }}" @selected(old('id_anggota') == $a->id_anggota)>{{ $a->nama }} ({{ $a->nis_nip }})</option>
                            @endforeach
                        </select>
                        <a href="{{ route('anggota.index') }}" target="_blank" class="small d-inline-block mt-2">
                            <i class="bi bi-person-plus"></i> Anggota belum terdaftar? Tambah dulu
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tgl_pinjam" id="tglPinjam" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="tgl_jatuh_tempo" id="tglJatuhTempo"
                               value="{{ old('tgl_jatuh_tempo', now()->addDays(config('perpustakaan.lama_pinjam'))->format('Y-m-d')) }}"
                               class="form-control" required>
                        <div class="form-text">Default {{ config('perpustakaan.lama_pinjam') }} hari dari tanggal pinjam.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Buku</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addRow"><i class="bi bi-plus-lg"></i> Tambah Buku</button>
                </div>
                <div class="card-body" id="bookRows"></div>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-primary px-4"><i class="bi bi-check2-circle me-1"></i> Simpan Peminjaman</button>
            </div>
        </div>
    </div>
</form>

<template id="rowTemplate">
    <div class="book-row row g-2 align-items-end mb-3">
        <div class="col-md-6">
            <label class="form-label">Buku</label>
            <select name="buku[__i__][id_buku]" class="form-select" required>
                <option value="">Pilih buku</option>
                @foreach ($buku as $b)
                    <option value="{{ $b->id_buku }}">{{ $b->kode_buku }} - {{ $b->judul }} (stok {{ $b->stok }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-4">
            <label class="form-label">Jumlah</label>
            <input type="number" name="buku[__i__][jumlah]" value="1" min="1" class="form-control" required>
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label">Kondisi</label>
            <select name="buku[__i__][kondisi]" class="form-select">
                <option value="baik">Baik</option>
                <option value="rusak">Rusak</option>
            </select>
        </div>
        <div class="col-md-1 col-2 text-end">
            <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    const wrap = document.getElementById('bookRows');
    const tpl = document.getElementById('rowTemplate').innerHTML;
    let idx = 0;

    function addRow() {
        wrap.insertAdjacentHTML('beforeend', tpl.replaceAll('__i__', idx++));
    }

    document.getElementById('addRow').addEventListener('click', addRow);
    wrap.addEventListener('click', e => {
        const btn = e.target.closest('.remove-row');
        if (btn && wrap.children.length > 1) btn.closest('.book-row').remove();
    });
    addRow();

    // jatuh tempo otomatis mengikuti tanggal pinjam
    const lama = {{ (int) config('perpustakaan.lama_pinjam') }};
    document.getElementById('tglPinjam').addEventListener('change', function () {
        if (!this.value) return;
        const d = new Date(this.value);
        d.setDate(d.getDate() + lama);
        document.getElementById('tglJatuhTempo').value = d.toISOString().slice(0, 10);
    });

    // filter anggota
    document.getElementById('cariAnggota').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        [...document.getElementById('selectAnggota').options].forEach(o => {
            if (o.value) o.hidden = !o.text.toLowerCase().includes(q);
        });
    });
</script>
@endpush