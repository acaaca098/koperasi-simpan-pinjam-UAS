<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = ['user_id', 'nomor_anggota', 'alamat', 'status'];

    // Nominal minimal simpanan yang harus dimiliki agar eligible mengajukan
    // pinjaman. Sesuaikan dengan aturan koperasi kamu / pindah ke config.
    protected const MIN_SIMPANAN_UNTUK_PINJAM = 500_000;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function simpanan(): HasMany
    {
        return $this->hasMany(Simpanan::class);
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(Pinjaman::class);
    }

    /**
     * Total saldo simpanan (semua jenis) milik anggota ini.
     * Dipakai SimpananService & PinjamanService::ajukan() untuk cek eligibilitas.
     */
    public function totalSimpanan(): float
    {
        return (float) $this->simpanan()->sum('saldo');
    }

    /**
     * Cek eligibilitas dasar sebelum boleh mengajukan pinjaman
     * (dipakai di PinjamanService, sesuai node "Cek Eligibilitas Simpanan"
     * pada activity diagram).
     */
    public function isEligibleForLoan(): bool
    {
        return $this->status === 'aktif'
            && $this->totalSimpanan() >= self::MIN_SIMPANAN_UNTUK_PINJAM;
    }
}
