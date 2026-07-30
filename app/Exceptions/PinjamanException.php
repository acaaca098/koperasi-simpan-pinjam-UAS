<?php

namespace App\Exceptions;

use Exception;

/**
 * Dilempar saat business rule seputar Pinjaman dilanggar
 * (mis. anggota belum eligible, transisi status tidak valid, dll).
 * Ditangkap di Controller / Filament action lalu ditampilkan sebagai
 * pesan yang ramah pengguna — bukan stack trace mentah.
 */
class PinjamanException extends Exception
{
    public static function tidakEligible(): self
    {
        return new self('Anda belum memenuhi syarat pengajuan pinjaman (saldo simpanan belum mencukupi).');
    }

    public static function statusTidakValid(string $statusSaatIni, string $aksi): self
    {
        return new self("Tidak bisa melakukan '{$aksi}' karena status pinjaman saat ini adalah '{$statusSaatIni}'.");
    }
}
