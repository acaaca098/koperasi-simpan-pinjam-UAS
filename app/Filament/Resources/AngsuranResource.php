<?php

namespace App\Filament\Resources;

use App\Exceptions\PinjamanException;
use App\Filament\Resources\AngsuranResource\Pages;
use App\Models\Angsuran;
use App\Services\AngsuranService;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Panel Pengurus untuk memverifikasi setoran angsuran yang di-upload Anggota.
 * Sesuai Use Case "Kelola Jadwal Angsuran" & sequence diagram Angsuran.
 */
class AngsuranResource extends Resource
{
    protected static ?string $model = Angsuran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Verifikasi Angsuran';

    protected static ?string $modelLabel = 'Angsuran';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pinjaman.anggota.nomor_anggota')
                    ->label('No. Anggota')
                    ->searchable(),

                TextColumn::make('angsuran_ke')
                    ->label('Angsuran Ke'),

                TextColumn::make('jumlah_tagihan')
                    ->label('Tagihan')
                    ->money('IDR'),

                TextColumn::make('denda')
                    ->label('Denda')
                    ->money('IDR'),

                TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d M Y'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'BELUM_BAYAR' => 'gray',
                        'MENUNGGU_VERIFIKASI' => 'warning',
                        'LUNAS' => 'success',
                        'TELAT' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('bukti_transfer_path')
                    ->label('Bukti Transfer')
                    ->formatStateUsing(fn ($state) => $state ? 'Lihat file' : '-')
                    ->url(fn (Angsuran $record) => $record->bukti_transfer_path
                        ? \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl($record->bukti_transfer_path, now()->addMinutes(10))
                        : null)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'BELUM_BAYAR' => 'Belum Bayar',
                    'MENUNGGU_VERIFIKASI' => 'Menunggu Verifikasi',
                    'LUNAS' => 'Lunas',
                    'TELAT' => 'Telat',
                ]),
            ])
            ->actions([
                Action::make('verifikasi')
                    ->label('Verifikasi Setoran')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Angsuran $record) => auth()->user()->can('verifikasi', $record))
                    ->action(function (Angsuran $record) {
                        try {
                            app(AngsuranService::class)->verifikasiSetoran($record);
                            Notification::make()->title('Setoran berhasil diverifikasi')->success()->send();
                        } catch (PinjamanException $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),
            ])
            ->defaultSort('jatuh_tempo', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAngsurans::route('/'),
            'create' => Pages\CreateAngsuran::route('/create'),
            'edit' => Pages\EditAngsuran::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // Angsuran dibuat otomatis oleh PinjamanService::cairkan(), bukan input manual.
        return false;
    }
}
