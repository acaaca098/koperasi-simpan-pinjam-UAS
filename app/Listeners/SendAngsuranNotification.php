<?php

namespace App\Listeners;

use App\Events\AngsuranStatusChanged;
use App\Models\User;
use App\Services\NotificationService;

class SendAngsuranNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(AngsuranStatusChanged $event): void
    {
        $angsuran = $event->angsuran;
        $anggotaUser = $angsuran->pinjaman->anggota->user;

        match ($angsuran->status) {
            'MENUNGGU_VERIFIKASI' => $this->notifyRole('pengurus', 'Setoran Angsuran Baru',
                "Angsuran ke-{$angsuran->angsuran_ke} untuk pinjaman #{$angsuran->pinjaman_id} menunggu verifikasi."),

            'LUNAS' => $this->notifications->kirim(
                $anggotaUser,
                'Angsuran Terverifikasi',
                "Angsuran ke-{$angsuran->angsuran_ke} Anda telah diverifikasi dan lunas."
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
