<?php

namespace App\Http\Controllers;

use App\Exceptions\PinjamanException;
use App\Models\Angsuran;
use App\Services\AngsuranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{
    public function __construct(private AngsuranService $angsuranService) {}

    public function bayar(Request $request, Angsuran $angsuran): RedirectResponse
    {
        $this->authorize('bayar', $angsuran);

        $data = $request->validate([
            'bukti_transfer' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            $this->angsuranService->bayar($angsuran, $request->file('bukti_transfer'));

            return back()->with('success', 'Bukti transfer terkirim, menunggu verifikasi Pengurus.');
        } catch (PinjamanException $e) {
            return back()->withErrors(['bukti_transfer' => $e->getMessage()]);
        }
    }

    public function verifikasi(Angsuran $angsuran): RedirectResponse
    {
        $this->authorize('verifikasi', $angsuran);

        try {
            $this->angsuranService->verifikasiSetoran($angsuran);

            return back()->with('success', 'Setoran berhasil diverifikasi.');
        } catch (PinjamanException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }
    }
}
