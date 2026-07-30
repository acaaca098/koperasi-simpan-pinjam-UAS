<?php

namespace App\Policies;

use App\Models\Angsuran;
use App\Models\User;

class AngsuranPolicy
{
    public function bayar(User $user, Angsuran $angsuran): bool
    {
        return $user->isAnggota()
            && $angsuran->pinjaman->anggota->user_id === $user->id
            && in_array($angsuran->status, ['BELUM_BAYAR', 'TELAT']);
    }

    public function verifikasi(User $user, Angsuran $angsuran): bool
    {
        return $user->isPengurus() && $angsuran->status === 'MENUNGGU_VERIFIKASI';
    }
}
