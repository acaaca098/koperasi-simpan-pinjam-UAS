<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Pinjaman;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Anggota', Anggota::count())
                ->description('Anggota Aktif Koperasi')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Pinjaman', 'Rp ' . number_format(Pinjaman::sum('jumlah_pengajuan'), 0, ',', '.'))
                ->description('Total dana dipinjam')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Diproses', Pinjaman::where('status', 'DIAJUKAN')->count())
                ->description('Menunggu Verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}