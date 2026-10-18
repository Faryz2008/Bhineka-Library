@extends('layouts.app')
@section('title', 'Data Buku')

@section('content')
<div class="page-head">
    <div>
        <h1>Data Buku</h1>
        <p>Kelola koleksi buku perpustakaan</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku
    </button>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul, kode, atau pengarang...">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id_kategori }}" @selected(request('kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>Kode</th><th>Judul</th><th>Pengarang</th><th>Penerbit</th><th>Tahun</th><th>Kategori</th><th>Stok</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($buku as $b)
                        <tr>
                            <td class="text-muted">{{ $b->kode_buku }}</td>
                            <td class="fw-medium">{{ $b->judul }}</td>
                            <td>{{ $b->pengarang }}</td>
                            <td>{{ $b->penerbit }}</td>
                            <td>{{ $b->tahun_terbit }}</td>
                            <td><span class="chip chip-blue">{{ $b->kategori->nama_kategori }}</span></td>
                            <td>
                                <span class="chip {{ $b->stok > 0 ? 'chip-green' : 'chip-red' }}">{{ $b->stok }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $b->id_buku }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('buku.destroy', $b) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Data buku belum ada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $buku->links() }}</div>
    </div>
</div>

{{-- Modal tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('buku.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Buku</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">@include('buku._form', ['buku' => null])</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal edit --}}
@foreach ($buku as $b)
<div class="modal fade" id="modalEdit{{ $b->id_buku }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('buku.update', $b) }}">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Ubah Buku</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">@include('buku._form', ['buku' => $b])</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection