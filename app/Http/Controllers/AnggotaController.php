<?php

namespace App\Http\Controllers;

use App\Imports\AnggotaImport;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $anggota = Anggota::query()
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('nama', 'like', "%{$s}%")
                ->orWhere('nis_nip', 'like', "%{$s}%")))
            ->when($request->jenis, fn ($q, $j) => $q->where('jenis', $j))
            ->orderBy('nama')
            ->paginate(10)->withQueryString();

        return view('anggota.index', compact('anggota'));
    }

    public function store(Request $request)
    {
        Anggota::create($this->validated($request));

        return back()->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function update(Request $request, Anggota $anggota)
    {
        $anggota->update($this->validated($request, $anggota));

        return back()->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->peminjaman()->exists()) {
            return back()->withErrors('Anggota memiliki riwayat peminjaman, tidak bisa dihapus.');
        }

        $anggota->delete();

        return back()->with('success', 'Data anggota berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:2048']);

        $import = new AnggotaImport;
        Excel::import($import, $request->file('file'));

        return back()->with('success', "{$import->jumlah} data anggota berhasil diimpor.");
    }

    private function validated(Request $request, ?Anggota $anggota = null): array
    {
        $data = $request->validate([
            'nis_nip' => ['required', 'string', 'max:30',
                Rule::unique('anggota', 'nis_nip')->ignore($anggota?->id_anggota, 'id_anggota')],
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:siswa,guru',
            'kelas' => ['required_if:jenis,siswa', 'nullable', 'string', 'max:20'],
            'no_telp' => 'nullable|string|max:20',
        ]);

        if ($data['jenis'] === 'guru') {
            $data['kelas'] = null;
        }

        return $data;
    }
}