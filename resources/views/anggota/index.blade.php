@extends('layouts.app')
@section('title', 'Data Anggota')

@section('content')
<div class="page-head">
    <div>
        <h1>Data Anggota</h1>
        <p>Kelola data siswa dan guru</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalImport">
            <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau NIS/NIP...">
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua jenis</option>
                    <option value="siswa" @selected(request('jenis') === 'siswa')>Siswa</option>
                    <option value="guru" @selected(request('jenis') === 'guru')>Guru</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>NIS / NIP</th><th>Nama</th><th>Jenis</th><th>Kelas</th><th>No. Telp</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($anggota as $a)
                        <tr>
                            <td class="text-muted">{{ $a->nis_nip }}</td>
                            <td class="fw-medium">{{ $a->nama }}</td>
                            <td><span class="chip {{ $a->jenis === 'guru' ? 'chip-gray' : 'chip-blue' }}">{{ ucfirst($a->jenis) }}</span></td>
                            <td>{{ $a->kelas ?: '-' }}</td>
                            <td>{{ $a->no_telp ?: '-' }}</td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $a->id_anggota }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('anggota.destroy', $a) }}" method="POST" class="d-inline"
                                      data-confirm-title="Hapus anggota?"
                                      data-confirm="Data yang dihapus tidak bisa dikembalikan."
                                      data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Data anggota belum ada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $anggota->links() }}</div>
    </div>
</div>

{{-- Modal tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('anggota.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Anggota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">@include('anggota._form', ['anggota' => null])</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal import --}}
<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('anggota.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Import Anggota dari Excel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p class="text-muted mb-2">Baris pertama harus berisi judul kolom berikut:</p>
                <code class="d-block mb-3 p-2 rounded" style="background:#e6f0fd;color:#0b2e63">nis_nip | nama | jenis | kelas | no_telp</code>
                <ul class="text-muted small ps-3">
                    <li><b>jenis</b> diisi <i>siswa</i> atau <i>guru</i>.</li>
                    <li>Kalau NIS/NIP sudah ada, datanya akan diperbarui.</li>
                    <li>Format kolom <b>no_telp</b> sebagai Text supaya angka 0 di depan tidak hilang.</li>
                </ul>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal edit --}}
@foreach ($anggota as $a)
<div class="modal fade" id="modalEdit{{ $a->id_anggota }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('anggota.update', $a) }}">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Ubah Anggota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">@include('anggota._form', ['anggota' => $a])</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection