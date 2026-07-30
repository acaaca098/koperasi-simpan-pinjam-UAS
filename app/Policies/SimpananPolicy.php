<?php

namespace App\Policies;

use App\Models\Simpanan;
use App\Models\User;

/**
 * Sesuai Use Case Diagram:
 * - Anggota: Setor Simpanan, Tarik Simpanan Sukarela, Lihat Saldo Simpanan
 *   (hanya untuk simpanan miliknya sendiri).
 * - Pengurus: Catat Setor/Tarik Simpanan (lewat Filament SimpananResource).
 */
class SimpananPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAnggota() || $user->isPengurus() || $user->isKetua();
    }

    public function view(User $user, Simpanan $simpanan): bool
    {
        if ($user->isAnggota()) {
            return $simpanan->anggota->user_id === $user->id;
        }

        return $user->isPengurus() || $user->isKetua();
    }

    public function create(User $user): bool
    {
        // Pengurus/Ketua yang boleh membuka rekening simpanan baru untuk anggota
        // (lewat Filament SimpananResource). Anggota tidak punya akses ini.
        return $user->isPengurus() || $user->isKetua();
    }

    public function update(User $user, Simpanan $simpanan): bool
    {
        return $user->isPengurus() || $user->isKetua();
    }

    public function setor(User $user, Simpanan $simpanan): bool
    {
        return $user->isAnggota() && $simpanan->anggota->user_id === $user->id;
    }

    public function tarik(User $user, Simpanan $simpanan): bool
    {
        return $user->isAnggota()
            && $simpanan->anggota->user_id === $user->id
            && $simpanan->jenis === 'sukarela';
    }
}