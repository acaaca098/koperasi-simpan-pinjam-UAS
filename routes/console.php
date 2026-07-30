<?php

use Illuminate\Support\Facades\Schedule;

// Scheduled job: syarat wajib Bagian 2 soal ("proses terjadwal/otomatis").
// Pastikan cron server memanggil `php artisan schedule:run` tiap menit
// (di Railway/Render/VPS: tambahkan scheduler/cron job terpisah).
Schedule::command('angsuran:cek-jatuh-tempo')->dailyAt('01:00');
