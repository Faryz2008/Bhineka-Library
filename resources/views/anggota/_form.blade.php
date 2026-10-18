@php $a = $anggota ?? null; @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">NIS / NIP</label>
        <input type="text" name="nis_nip" value="{{ $a->nis_nip ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="{{ $a->nama ?? '' }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select" required>
            <option value="siswa" @selected(($a->jenis ?? 'siswa') === 'siswa')>Siswa</option>
            <option value="guru" @selected(($a->jenis ?? '') === 'guru')>Guru</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Kelas <span class="text-muted fw-normal">(siswa)</span></label>
        <input type="text" name="kelas" value="{{ $a->kelas ?? '' }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="no_telp" value="{{ $a->no_telp ?? '' }}" class="form-control">
    </div>
</div>