<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $buku = Buku::with('kategori')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('judul', 'like', "%{$s}%")
                ->orWhere('kode_buku', 'like', "%{$s}%")
                ->orWhere('pengarang', 'like', "%{$s}%")))
            ->when($request->kategori, fn ($q, $k) => $q->where('id_kategori', $k))
            ->orderBy('judul')
            ->paginate(10)->withQueryString();

        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('buku.index', compact('buku', 'kategori'));
    }

    public function store(Request $request)
    {
        Buku::create($this->validated($request));

        return back()->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($this->validated($request, $buku));

        return back()->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if (DetailPeminjaman::where('id_buku', $buku->id_buku)->exists()) {
            return back()->withErrors('Buku sudah pernah dipinjam, tidak bisa dihapus.');
        }

        $buku->delete();

        return back()->with('success', 'Data buku berhasil dihapus.');
    }

    private function validated(Request $request, ?Buku $buku = null): array
    {
        return $request->validate([
            'kode_buku' => ['required', 'string', 'max:30',
                Rule::unique('buku', 'kode_buku')->ignore($buku?->id_buku, 'id_buku')],
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id_kategori',
        ]);
    }
}