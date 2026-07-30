<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran';

    protected $fillable = [
        'pinjaman_id', 'angsuran_ke', 'jumlah_tagihan', 'denda',
        'jatuh_tempo', 'bukti_transfer_path', 'status',
        'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_tagihan' => 'decimal:2',
            'denda' => 'decimal:2',
            'jatuh_tempo' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    protected const PERSEN_DENDA_PER_HARI = 0.001; // 0.1%/hari, sesuaikan aturan koperasi

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isTelat(): bool
    {
        return $this->status === 'BELUM_BAYAR' && $this->jatuh_tempo->isPast();
    }

    public function hitungDenda(): float
    {
        if (! $this->isTelat()) {
            return 0.0;
        }

        $hariTelat = now()->diffInDays($this->jatuh_tempo);

        return (float) $this->jumlah_tagihan * self::PERSEN_DENDA_PER_HARI * $hariTelat;
    }
}
