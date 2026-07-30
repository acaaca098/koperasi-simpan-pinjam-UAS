<?php

namespace App\Console\Commands;

use App\Services\AngsuranService;
use Illuminate\Console\Command;

/**
 * Proses terjadwal/otomatis di background (syarat wajib Bagian 2 soal).
 * Dijalankan tiap hari untuk menandai angsuran yang lewat jatuh tempo
 * sebagai TELAT dan menghitung dendanya.
 *
 * Daftarkan di routes/console.php (Laravel 11+):
 *   Schedule::command('angsuran:cek-jatuh-tempo')->dailyAt('01:00');
 */
class CekJatuhTempoAngsuran extends Command
{
    protected $signature = 'angsuran:cek-jatuh-tempo';

    protected $description = 'Tandai angsuran yang lewat jatuh tempo sebagai TELAT dan hitung dendanya';

    public function handle(AngsuranService $angsuranService): int
    {
        $jumlah = $angsuranService->cekJatuhTempo();

        $this->info("Selesai. {$jumlah} angsuran ditandai TELAT.");

        return self::SUCCESS;
    }
}
