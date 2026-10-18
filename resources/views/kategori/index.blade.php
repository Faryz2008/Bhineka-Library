@extends('layouts.app')
@section('title', 'Data Kategori')

@section('content')
<div class="page-head">
    <div>
        <h1>Data Kategori</h1>
        <p>Kelola kategori buku perpustakaan</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </button>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kategori...">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Buku</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $k)
                        <tr>
                            <td>{{ $kategori->firstItem() + $loop->index }}</td>
                            <td class="fw-medium">{{ $k->nama_kategori }}</td>
                            <td class="text-muted">{{ $k->deskripsi ?: '-' }}</td>
                            <td><span class="chip chip-blue">{{ $k->buku_count }} buku</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id_kategori }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                                               <form action="{{ route('kategori.destroy', $k) }}" method="POST" class="d-inline"
                                      data-confirm-title="Hapus kategori?"
                                      data-confirm="Data yang dihapus tidak bisa dikembalikan."
                                      data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Data kategori belum ada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $kategori->links() }}</div>
    </div>
</div>

{{-- Modal tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('kategori.store') }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" required>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal edit --}}
@foreach ($kategori as $k)
<div class="modal fade" id="modalEdit{{ $k->id_kategori }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="{{ route('kategori.update', $k) }}">
            @csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title">Ubah Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ $k->nama_kategori }}" class="form-control" required>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control">{{ $k->deskripsi }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection