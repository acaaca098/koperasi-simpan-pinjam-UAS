<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Simpanan extends Model
{
    use HasFactory;

    protected $table = 'simpanan';

    protected $fillable = ['anggota_id', 'jenis', 'saldo'];

    protected function casts(): array
    {
        return ['saldo' => 'decimal:2'];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(SimpananTransaksi::class);
    }

    /**
     * Cek apakah simpanan ini boleh ditarik sejumlah $jumlah.
     * Sesuai aturan koperasi: hanya jenis "sukarela" yang bisa ditarik
     * bebas (simpanan pokok & wajib tidak bisa ditarik selama jadi anggota),
     * dan saldo harus cukup.
     */
    public function bisaDitarik(float $jumlah): bool
    {
        return $this->jenis === 'sukarela' && (float) $this->saldo >= $jumlah;
    }
}