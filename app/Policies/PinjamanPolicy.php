<?php

namespace App\Policies;

use App\Models\Pinjaman;
use App\Models\User;

/**
 * Sesuai Use Case Diagram:
 * - Anggota: Ajukan Pinjaman, lihat pinjaman miliknya sendiri
 * - Pengurus: Verifikasi Pengajuan Pinjaman, Approve/Reject Pinjaman Kecil
 * - Ketua: Approve/Reject Pinjaman Besar (extend dari verifikasi Pengurus)
 */
class PinjamanPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // masing-masing scope dibatasi di query, lihat controller
    }

    public function view(User $user, Pinjaman $pinjaman): bool
    {
        if ($user->isAnggota()) {
            return $pinjaman->anggota->user_id === $user->id;
        }

        return $user->isPengurus() || $user->isKetua();
    }

    public function create(User $user): bool
    {
        return $user->isAnggota();
    }

    public function verifikasi(User $user, Pinjaman $pinjaman): bool
    {
        return $user->isPengurus() && $pinjaman->status === 'DIAJUKAN';
    }

    public function approve(User $user, Pinjaman $pinjaman): bool
    {
        // Pinjaman kecil: Pengurus sudah cukup (ditangani otomatis di service).
        // Pinjaman besar (di atas threshold): wajib Ketua.
        if ($pinjaman->needsKetuaApproval()) {
            return $user->isKetua() && $pinjaman->status === 'DIVERIFIKASI';
        }

        return $user->isPengurus() && $pinjaman->status === 'DIVERIFIKASI';
    }

    public function cairkan(User $user, Pinjaman $pinjaman): bool
    {
        return $user->isPengurus() && $pinjaman->status === 'DISETUJUI';
    }
}
