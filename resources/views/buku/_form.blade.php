@php $b = $buku ?? null; @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Buku</label>
        <input type="text" name="kode_buku" value="{{ $b->kode_buku ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-8">
        <label class="form-label">Judul</label>
        <input type="text" name="judul" value="{{ $b->judul ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Pengarang</label>
        <input type="text" name="pengarang" value="{{ $b->pengarang ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Penerbit</label>
        <input type="text" name="penerbit" value="{{ $b->penerbit ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" value="{{ $b->tahun_terbit ?? '' }}" class="form-control" min="1900" max="{{ date('Y') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" value="{{ $b->stok ?? 0 }}" class="form-control" min="0" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <select name="id_kategori" class="form-select" required>
            <option value="">Pilih kategori</option>
            @foreach ($kategori as $k)
                <option value="{{ $k->id_kategori }}" @selected(($b->id_kategori ?? null) == $k->id_kategori)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
</div>