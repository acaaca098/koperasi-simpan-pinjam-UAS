<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'anggota_id', 'jumlah_pengajuan', 'tenor_bulan', 'bunga_persen',
        'jaminan_path', 'status', 'verified_by', 'verified_at',
        'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_pengajuan' => 'decimal:2',
            'bunga_persen' => 'decimal:2',
            'verified_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    // Threshold nominal yang menentukan apakah butuh approval Ketua.
    // Sesuai node "Cek Threshold Nominal" / "Di atas threshold?" pada
    // activity diagram dan needsKetuaApproval() di class diagram.
    public const THRESHOLD_APPROVAL_KETUA = 5_000_000;

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function angsuran(): HasMany
    {
        return $this->hasMany(Angsuran::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hitungBunga(): float
    {
        return (float) $this->jumlah_pengajuan * ((float) $this->bunga_persen / 100) * $this->tenor_bulan;
    }

    public function needsKetuaApproval(): bool
    {
        return (float) $this->jumlah_pengajuan > self::THRESHOLD_APPROVAL_KETUA;
    }
}
