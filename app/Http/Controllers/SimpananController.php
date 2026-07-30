<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Services\SimpananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function __construct(private SimpananService $simpananService) {}

    public function index()
    {
        $anggota = auth()->user()->anggota;

        $simpanan = $anggota
            ? $anggota->simpanan()->with(['transaksi' => fn ($q) => $q->latest('tanggal')->limit(5)])->get()
            : collect();

        return view('simpanan.index', [
            'anggota' => $anggota,
            'simpanan' => $simpanan,
        ]);
    }

    public function setor(Request $request, Simpanan $simpanan): RedirectResponse
    {
        $this->authorize('setor', $simpanan);

        $data = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:10000'],
        ]);

        $this->simpananService->setor($simpanan, (float) $data['jumlah']);

        return back()->with('success', 'Setoran simpanan berhasil, saldo sudah diperbarui.');
    }

    public function tarik(Request $request, Simpanan $simpanan): RedirectResponse
    {
        $this->authorize('tarik', $simpanan);

        $data = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:10000'],
        ]);

        if (! $simpanan->bisaDitarik((float) $data['jumlah'])) {
            return back()->withErrors([
                'jumlah' => 'Penarikan gagal: saldo tidak cukup untuk jenis simpanan ini.',
            ]);
        }

        $this->simpananService->tarik($simpanan, (float) $data['jumlah']);

        return back()->with('success', 'Penarikan simpanan berhasil diproses.');
    }
}