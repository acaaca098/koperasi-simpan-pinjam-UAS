<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpananResource\Pages;
use App\Models\Simpanan;
use App\Services\SimpananService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Panel Pengurus untuk "Catat Setor/Tarik Simpanan" (sesuai Use Case Diagram,
 * fitur Pengurus). Anggota sendiri melakukan setor/tarik lewat halaman web
 * biasa (SimpananController), resource ini untuk pencatatan oleh Pengurus
 * (mis. setoran tunai yang dibawa langsung ke kantor koperasi).
 */
class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Simpanan Anggota';

    protected static ?string $modelLabel = 'Simpanan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('anggota_id')
                ->relationship('anggota', 'nomor_anggota')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('jenis')
                ->options([
                    'pokok' => 'Simpanan Pokok',
                    'wajib' => 'Simpanan Wajib',
                    'sukarela' => 'Simpanan Sukarela',
                ])
                ->required(),

            TextInput::make('saldo')
                ->numeric()
                ->prefix('Rp')
                ->required(),
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
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('jenis')
                    ->badge(),

                TextColumn::make('saldo')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jenis')->options([
                    'pokok' => 'Pokok',
                    'wajib' => 'Wajib',
                    'sukarela' => 'Sukarela',
                ]),
            ])
            ->actions([
                Action::make('setor')
                    ->label('Catat Setor')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('jumlah')->numeric()->required()->minValue(10000)->prefix('Rp'),
                    ])
                    ->visible(fn (Simpanan $record) => auth()->user()->can('view', $record))
                    ->action(function (Simpanan $record, array $data) {
                        app(SimpananService::class)->setor($record, (float) $data['jumlah']);
                        Notification::make()->title('Setoran berhasil dicatat')->success()->send();
                    }),

                Action::make('tarik')
                    ->label('Catat Tarik')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('warning')
                    ->form([
                        TextInput::make('jumlah')->numeric()->required()->minValue(10000)->prefix('Rp'),
                    ])
                    ->visible(fn (Simpanan $record) => $record->jenis === 'sukarela' && auth()->user()->can('view', $record))
                    ->action(function (Simpanan $record, array $data) {
                        if (! $record->bisaDitarik((float) $data['jumlah'])) {
                            Notification::make()->title('Saldo tidak cukup')->danger()->send();

                            return;
                        }
                        app(SimpananService::class)->tarik($record, (float) $data['jumlah']);
                        Notification::make()->title('Penarikan berhasil dicatat')->success()->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSimpanans::route('/'),
            'create' => Pages\CreateSimpanan::route('/create'),
            'edit' => Pages\EditSimpanan::route('/{record}/edit'),
        ];
    }
}
