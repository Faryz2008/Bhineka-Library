<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::withCount('buku')
            ->when($request->q, fn ($q, $s) => $q->where('nama_kategori', 'like', "%{$s}%"))
            ->orderBy('nama_kategori')
            ->paginate(10)->withQueryString();

        return view('kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        Kategori::create($this->validated($request));

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($this->validated($request));

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->buku()->exists()) {
            return back()->withErrors('Kategori masih dipakai oleh data buku, tidak bisa dihapus.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
        ]);
    }
}