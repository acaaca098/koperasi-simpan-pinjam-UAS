<?php

namespace App\Http\Controllers;

use App\Exceptions\PinjamanException;
use App\Models\Pinjaman;
use App\Services\PinjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function __construct(private PinjamanService $pinjamanService) {}

    public function index()
    {
        $this->authorize('viewAny', Pinjaman::class);

        $anggota = auth()->user()->anggota;
        $pinjaman = $anggota->pinjaman()->with('angsuran')->latest()->paginate(10);

        return view('pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        $this->authorize('create', Pinjaman::class);

        return view('pinjaman.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Pinjaman::class);

        $data = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:100000'],
            'tenor_bulan' => ['required', 'integer', 'min:1', 'max:36'],
            'jaminan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            $this->pinjamanService->ajukan(
                auth()->user()->anggota,
                $data['jumlah'],
                $data['tenor_bulan'],
                $request->file('jaminan'),
            );

            return redirect()->route('pinjaman.index')
                ->with('success', 'Pengajuan pinjaman berhasil dikirim, menunggu verifikasi Pengurus.');
        } catch (PinjamanException $e) {
            // Penanganan error yang layak: pesan ramah, bukan stack trace mentah.
            return back()->withErrors(['jumlah' => $e->getMessage()]);
        }
    }

    public function verifikasi(Pinjaman $pinjaman): RedirectResponse
    {
        $this->authorize('verifikasi', $pinjaman);

        try {
            $this->pinjamanService->verifikasiPengurus($pinjaman);

            return back()->with('success', 'Pengajuan berhasil diverifikasi.');
        } catch (PinjamanException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }
    }

    public function approve(Pinjaman $pinjaman): RedirectResponse
    {
        $this->authorize('approve', $pinjaman);

        try {
            $this->pinjamanService->approvalKetua($pinjaman);

            return back()->with('success', 'Pinjaman disetujui.');
        } catch (PinjamanException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }
    }

    public function cairkan(Pinjaman $pinjaman): RedirectResponse
    {
        $this->authorize('cairkan', $pinjaman);

        try {
            $this->pinjamanService->cairkan($pinjaman);

            return back()->with('success', 'Dana pinjaman berhasil dicairkan.');
        } catch (PinjamanException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }
    }
}
