<?php

namespace App\Services;

use App\Events\AngsuranStatusChanged;
use App\Exceptions\PinjamanException;
use App\Models\Angsuran;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Sesuai class diagram: AngsuranService.bayar(angsuran, jumlah): void,
 * cekJatuhTempo(): void, dan sequence diagram "Angsuran".
 */
class AngsuranService
{
    /**
     * Anggota upload bukti transfer -> status MENUNGGU_VERIFIKASI.
     */
    public function bayar(Angsuran $angsuran, UploadedFile $buktiTransfer): void
    {
        if ($angsuran->status !== 'BELUM_BAYAR' && $angsuran->status !== 'TELAT') {
            throw PinjamanException::statusTidakValid($angsuran->status, 'pembayaran angsuran');
        }

        $path = $buktiTransfer->store('bukti-transfer', 's3');
        $statusSebelumnya = $angsuran->status;

        $angsuran->update([
            'bukti_transfer_path' => $path,
            'status' => 'MENUNGGU_VERIFIKASI',
        ]);

        event(new AngsuranStatusChanged($angsuran, $statusSebelumnya));
    }

    /**
     * Pengurus memverifikasi setoran. Jika ini angsuran terakhir yang lunas,
     * status Pinjaman induk juga diupdate jadi LUNAS (sesuai blok [alt] di
     * sequence diagram).
     */
    public function verifikasiSetoran(Angsuran $angsuran): void
    {
        if ($angsuran->status !== 'MENUNGGU_VERIFIKASI') {
            throw PinjamanException::statusTidakValid($angsuran->status, 'verifikasi setoran');
        }

        DB::transaction(function () use ($angsuran) {
            $statusSebelumnya = $angsuran->status;

            $angsuran->update([
                'status' => 'LUNAS',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $pinjaman = $angsuran->pinjaman;
            $semuaLunas = ! $pinjaman->angsuran()->where('status', '!=', 'LUNAS')->exists();

            if ($semuaLunas) {
                $pinjaman->update(['status' => 'LUNAS']);
            }

            event(new AngsuranStatusChanged($angsuran, $statusSebelumnya));
        });
    }

    /**
     * Dipanggil oleh scheduled command (lihat app/Console/Commands) setiap
     * hari untuk menandai angsuran yang lewat jatuh tempo dan menghitung
     * denda otomatis. Ini "proses terjadwal/otomatis di background" yang
     * diwajibkan soal (Bagian 2).
     */
    public function cekJatuhTempo(): int
    {
        $angsuranTelat = Angsuran::where('status', 'BELUM_BAYAR')
            ->whereDate('jatuh_tempo', '<', now())
            ->get();

        foreach ($angsuranTelat as $angsuran) {
            $angsuran->update([
                'status' => 'TELAT',
                'denda' => $angsuran->hitungDenda(),
            ]);
        }

        return $angsuranTelat->count();
    }
}
