<?php

namespace App\Filament\Resources;

use App\Exceptions\PinjamanException;
use App\Filament\Resources\PinjamanResource\Pages;
use App\Models\Pinjaman;
use App\Services\PinjamanService;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Panel Pengurus & Ketua untuk mengelola pengajuan pinjaman.
 * Sesuai Use Case Diagram: Pengurus (Verifikasi Pengajuan Pinjaman,
 * Approve/Reject Pinjaman Kecil), Ketua (Approve/Reject Pinjaman Besar).
 * RBAC nyata ditentukan oleh PinjamanPolicy (bukan cuma disembunyikan di UI).
 */
class PinjamanResource extends Resource
{
    protected static ?string $model = Pinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pengajuan Pinjaman';

    protected static ?string $modelLabel = 'Pinjaman';

    public static function form(Form $form): Form
    {
        // Resource ini dipakai untuk review/verifikasi, bukan input manual,
        // jadi field dibuat read-only kecuali yang memang bisa diubah lewat aksi.
        return $form->schema([
            Placeholder::make('anggota')
                ->label('Anggota')
                ->content(fn (?Pinjaman $record) => $record?->anggota?->nomor_anggota.' - '.$record?->anggota?->user?->name),

            TextInput::make('jumlah_pengajuan')
                ->label('Jumlah Pengajuan (Rp)')
                ->disabled()
                ->numeric(),

            TextInput::make('tenor_bulan')
                ->label('Tenor (bulan)')
                ->disabled(),

            Select::make('status')
                ->options([
                    'DIAJUKAN' => 'Diajukan',
                    'DIVERIFIKASI' => 'Diverifikasi',
                    'DISETUJUI' => 'Disetujui',
                    'DITOLAK' => 'Ditolak',
                    'DICAIRKAN' => 'Dicairkan',
                    'LUNAS' => 'Lunas',
                ])
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anggota.nomor_anggota')
                    ->label('No. Anggota')
                    ->searchable(),

                TextColumn::make('anggota.user.name')
                    ->label('Nama Anggota')
                    ->searchable(),

                TextColumn::make('jumlah_pengajuan')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tenor_bulan')
                    ->label('Tenor')
                    ->suffix(' bln'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DIAJUKAN' => 'gray',
                        'DIVERIFIKASI' => 'info',
                        'DISETUJUI' => 'warning',
                        'DICAIRKAN' => 'success',
                        'LUNAS' => 'success',
                        'DITOLAK' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'DIAJUKAN' => 'Diajukan',
                    'DIVERIFIKASI' => 'Diverifikasi',
                    'DISETUJUI' => 'Disetujui',
                    'DITOLAK' => 'Ditolak',
                    'DICAIRKAN' => 'Dicairkan',
                    'LUNAS' => 'Lunas',
                ]),
            ])
            ->actions([
                // Sesuai Use Case: Pengurus verifikasi pengajuan (yang masih DIAJUKAN).
                Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Pinjaman $record) => auth()->user()->can('verifikasi', $record))
                    ->action(function (Pinjaman $record) {
                        try {
                            app(PinjamanService::class)->verifikasiPengurus($record);
                            Notification::make()->title('Pengajuan berhasil diverifikasi')->success()->send();
                        } catch (PinjamanException $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),

                // Approve: kalau nominal besar hanya Ketua yang bisa (dicek PinjamanPolicy::approve,
                // bukan cuma disembunyikan di sini), kalau kecil Pengurus juga bisa.
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Pinjaman $record) => auth()->user()->can('approve', $record))
                    ->action(function (Pinjaman $record) {
                        try {
                            app(PinjamanService::class)->approvalKetua($record);
                            Notification::make()->title('Pinjaman disetujui')->success()->send();
                        } catch (PinjamanException $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Pinjaman $record) => auth()->user()->can('approve', $record))
                    ->action(function (Pinjaman $record) {
                        app(PinjamanService::class)->tolak($record);
                        Notification::make()->title('Pinjaman ditolak')->success()->send();
                    }),

                // Pencairan dana: hanya Pengurus, hanya status DISETUJUI.
                Action::make('cairkan')
                    ->label('Cairkan Dana')
                    ->icon('heroicon-o-banknotes')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn (Pinjaman $record) => auth()->user()->can('cairkan', $record))
                    ->action(function (Pinjaman $record) {
                        try {
                            app(PinjamanService::class)->cairkan($record);
                            Notification::make()->title('Dana berhasil dicairkan')->success()->send();
                        } catch (PinjamanException $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        // Ketua hanya perlu fokus ke pengajuan yang butuh approval dia,
        // tapi tetap boleh melihat semua untuk transparansi (dibatasi lewat Policy view()).
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPinjamans::route('/'),
            'create' => Pages\CreatePinjaman::route('/create'),
            'edit' => Pages\EditPinjaman::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // Pengajuan pinjaman dibuat lewat form Anggota (web biasa), bukan lewat panel ini.
        return false;
    }
}
