<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimpananTransaksi extends Model
{
    use HasFactory;

    protected $table = 'simpanan_transaksi';

    protected $fillable = ['simpanan_id', 'jenis', 'jumlah', 'tanggal'];

    protected function casts(): array
    {
        return ['jumlah' => 'decimal:2', 'tanggal' => 'date'];
    }

    public function simpanan(): BelongsTo
    {
        return $this->belongsTo(Simpanan::class);
    }
}
