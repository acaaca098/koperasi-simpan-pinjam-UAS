<?php

namespace App\Services;

use App\Events\PinjamanStatusChanged;
use App\Exceptions\PinjamanException;
use App\Models\Anggota;
use App\Models\Pinjaman;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Implementasi PinjamanService sesuai class diagram (ajukan, verifikasiPengurus,
 * approvalKetua, cairkan) dan sequence diagram "Pengajuan Pinjaman".
 */
class PinjamanService
{
    /**
     * Anggota mengajukan pinjaman + upload jaminan.
     * Mengikuti: BEGIN TRANSACTION -> Cek eligibilitas -> Simpan (DIAJUKAN) -> COMMIT
     * -> dispatch(PinjamanStatusChanged) -> notifikasi ke Pengurus.
     */
    public function ajukan(Anggota $anggota, float $jumlah, int $tenorBulan, UploadedFile $jaminan): Pinjaman
    {
        if (! $anggota->isEligibleForLoan()) {
            throw PinjamanException::tidakEligible();
        }

        return DB::transaction(function () use ($anggota, $jumlah, $tenorBulan, $jaminan) {
            $path = $jaminan->store('jaminan', 's3'); // object storage, lihat filesystems.php

            $pinjaman = Pinjaman::create([
                'anggota_id' => $anggota->id,
                'jumlah_pengajuan' => $jumlah,
                'tenor_bulan' => $tenorBulan,
                'jaminan_path' => $path,
                'status' => 'DIAJUKAN',
            ]);

            event(new PinjamanStatusChanged($pinjaman, statusSebelumnya: 'BARU'));

            return $pinjaman;
        });
    }

    /**
     * Pengurus memverifikasi pengajuan. Jika di atas threshold, status berhenti
     * di DIVERIFIKASI menunggu approval Ketua; jika tidak, langsung DISETUJUI.
     */
    public function verifikasiPengurus(Pinjaman $pinjaman): void
    {
        if ($pinjaman->status !== 'DIAJUKAN') {
            throw PinjamanException::statusTidakValid($pinjaman->status, 'verifikasi');
        }

        $statusSebelumnya = $pinjaman->status;

        $pinjaman->update([
            'status' => 'DIVERIFIKASI',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        if ($pinjaman->needsKetuaApproval()) {
            // Butuh approval Ketua -> beri tahu Ketua, tunggu approvalKetua()
            event(new PinjamanStatusChanged($pinjaman, $statusSebelumnya));

            return;
        }

        // Nominal kecil -> Pengurus langsung bisa menyetujui
        $pinjaman->update(['status' => 'DISETUJUI']);
        event(new PinjamanStatusChanged($pinjaman, 'DIVERIFIKASI'));
    }

    /**
     * Ketua menyetujui pengajuan bernominal besar (di atas threshold).
     */
    public function approvalKetua(Pinjaman $pinjaman): void
    {
        if ($pinjaman->status !== 'DIVERIFIKASI') {
            throw PinjamanException::statusTidakValid($pinjaman->status, 'approval ketua');
        }

        $statusSebelumnya = $pinjaman->status;

        $pinjaman->update([
            'status' => 'DISETUJUI',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        event(new PinjamanStatusChanged($pinjaman, $statusSebelumnya));
    }

    public function tolak(Pinjaman $pinjaman): void
    {
        $statusSebelumnya = $pinjaman->status;
        $pinjaman->update(['status' => 'DITOLAK']);
        event(new PinjamanStatusChanged($pinjaman, $statusSebelumnya));
    }

    /**
     * Pencairan dana setelah DISETUJUI, sekaligus generate jadwal angsuran.
     */
    public function cairkan(Pinjaman $pinjaman): void
    {
        if ($pinjaman->status !== 'DISETUJUI') {
            throw PinjamanException::statusTidakValid($pinjaman->status, 'pencairan');
        }

        DB::transaction(function () use ($pinjaman) {
            $pinjaman->update(['status' => 'DICAIRKAN']);
            $this->generateJadwalAngsuran($pinjaman);
        });

        event(new PinjamanStatusChanged($pinjaman, 'DISETUJUI'));
    }

    private function generateJadwalAngsuran(Pinjaman $pinjaman): void
    {
        $totalBayar = (float) $pinjaman->jumlah_pengajuan + $pinjaman->hitungBunga();
        $cicilanPerBulan = round($totalBayar / $pinjaman->tenor_bulan, 2);

        for ($i = 1; $i <= $pinjaman->tenor_bulan; $i++) {
            $pinjaman->angsuran()->create([
                'angsuran_ke' => $i,
                'jumlah_tagihan' => $cicilanPerBulan,
                'jatuh_tempo' => now()->addMonths($i)->endOfMonth(),
                'status' => 'BELUM_BAYAR',
            ]);
        }
    }
}
