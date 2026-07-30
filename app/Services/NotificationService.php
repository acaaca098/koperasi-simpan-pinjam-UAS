<?php

namespace App\Services;

use App\Mail\StatusUpdateMail;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/**
 * Sesuai class diagram: NotificationService.kirim(user, judul, pesan): void
 * Bertanggung jawab untuk (1) menyimpan notifikasi in-app, dan
 * (2) mengirim email. Dipanggil oleh Listener saat event status berubah.
 */
class NotificationService
{
    public function kirim(User $user, string $judul, string $pesan): void
    {
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'pesan' => $pesan,
            'dibaca' => false,
        ]);

        Mail::to($user->email)->queue(new StatusUpdateMail($judul, $pesan));
    }
}
