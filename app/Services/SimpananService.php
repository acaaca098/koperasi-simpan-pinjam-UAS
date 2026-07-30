<?php

namespace App\Services;

use App\Models\Simpanan;
use Illuminate\Support\Facades\DB;

class SimpananService
{
    public function setor(Simpanan $simpanan, float $jumlah): void
    {
        DB::transaction(function () use ($simpanan, $jumlah) {
            $simpanan->transaksi()->create([
                'jenis' => 'setor',
                'jumlah' => $jumlah,
                'tanggal' => now()->toDateString(),
            ]);

            $simpanan->increment('saldo', $jumlah);
        });
    }

    public function tarik(Simpanan $simpanan, float $jumlah): void
    {
        DB::transaction(function () use ($simpanan, $jumlah) {
            $simpanan->transaksi()->create([
                'jenis' => 'tarik',
                'jumlah' => $jumlah,
                'tanggal' => now()->toDateString(),
            ]);

            $simpanan->decrement('saldo', $jumlah);
        });
    }

    /**
     * Simulasi bunga bulanan untuk simpanan sukarela. Nilai bunga bisa
     * dipindah ke config/koperasi.php bila ingin dibuat dinamis.
     */
    public function hitungBungaBulanan(Simpanan $simpanan): float
    {
        $persenBunga = 0.005; // 0.5% / bulan, khusus simpanan sukarela

        return $simpanan->jenis === 'sukarela'
            ? (float) $simpanan->saldo * $persenBunga
            : 0.0;
    }
}
