<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with('anggota')
            ->withSum('details as total_buku', 'jumlah_pinjam')
            ->when($request->q, fn ($q, $s) => $q->whereHas('anggota', fn ($a) => $a
                ->where('nama', 'like', "%{$s}%")
                ->orWhere('nis_nip', 'like', "%{$s}%")))
            ->when($request->status, function ($q, $s) {
                return $s === 'terlambat'
                    ? $q->where('status', 'dipinjam')->whereDate('tgl_jatuh_tempo', '<', today())
                    : $q->where('status', $s);
            })
            ->latest('id_peminjaman')
            ->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();
        $buku = Buku::where('stok', '>', 0)->orderBy('judul')->get();

        return view('peminjaman.create', compact('anggota', 'buku'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tgl_pinjam' => 'required|date',
            'tgl_jatuh_tempo' => 'required|date|after_or_equal:tgl_pinjam',
            'buku' => 'required|array|min:1',
            'buku.*.id_buku' => 'required|exists:buku,id_buku|distinct',
            'buku.*.jumlah' => 'required|integer|min:1',
            'buku.*.kondisi' => 'required|in:baik,rusak',
        ]);

        DB::transaction(function () use ($data) {
            $peminjaman = Peminjaman::create([
                'id_anggota' => $data['id_anggota'],
                'id_user' => Auth::id(),
                'tgl_pinjam' => $data['tgl_pinjam'],
                'tgl_jatuh_tempo' => $data['tgl_jatuh_tempo'],
                'status' => 'dipinjam',
            ]);

            foreach ($data['buku'] as $item) {
                $buku = Buku::lockForUpdate()->find($item['id_buku']);

                if ($buku->stok < $item['jumlah']) {
                    throw ValidationException::withMessages([
                        'buku' => "Stok buku \"{$buku->judul}\" tidak cukup (tersisa {$buku->stok}).",
                    ]);
                }

                $buku->decrement('stok', $item['jumlah']);

                $peminjaman->details()->create([
                    'id_buku' => $buku->id_buku,
                    'jumlah_pinjam' => $item['jumlah'],
                    'kondisi_pinjam' => $item['kondisi'],
                ]);
            }
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disimpan.');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['anggota', 'user', 'details.buku', 'pengembalian.user']);

        return view('peminjaman.show', compact('peminjaman'));
    }
}