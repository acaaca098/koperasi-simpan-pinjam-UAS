<?php

namespace App\Listeners;

use App\Events\PinjamanStatusChanged;
use App\Models\User;
use App\Services\NotificationService;

/**
 * Menentukan siapa yang perlu diberi tahu setiap kali status Pinjaman
 * berubah, sesuai alur di sequence diagram:
 * DIAJUKAN -> notif ke Pengurus
 * DIVERIFIKASI (butuh approval besar) -> notif ke Ketua
 * DISETUJUI/DITOLAK/DICAIRKAN -> notif ke Anggota pemohon
 */
class SendPinjamanNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(PinjamanStatusChanged $event): void
    {
        $pinjaman = $event->pinjaman;

        match ($pinjaman->status) {
            'DIAJUKAN' => $this->notifyRole('pengurus', 'Pengajuan Pinjaman Baru',
                "Anggota {$pinjaman->anggota->nomor_anggota} mengajukan pinjaman Rp {$pinjaman->jumlah_pengajuan}."),

            'DIVERIFIKASI' => $pinjaman->needsKetuaApproval()
                ? $this->notifyRole('ketua', 'Menunggu Approval Anda',
                    "Pengajuan pinjaman #{$pinjaman->id} di atas threshold, butuh persetujuan Anda.")
                : null,

            'DISETUJUI', 'DITOLAK', 'DICAIRKAN', 'LUNAS' => $this->notifications->kirim(
                $pinjaman->anggota->user,
                "Status Pinjaman: {$pinjaman->status}",
                "Pengajuan pinjaman Anda sebesar Rp {$pinjaman->jumlah_pengajuan} kini berstatus {$pinjaman->status}."
            ),

            default => null,
        };
    }

    private function notifyRole(string $role, string $judul, string $pesan): void
    {
        User::where('role', $role)->get()
            ->each(fn (User $user) => $this->notifications->kirim($user, $judul, $pesan));
    }
}
