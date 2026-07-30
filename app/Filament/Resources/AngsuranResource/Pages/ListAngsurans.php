<?php

namespace App\Filament\Resources\AngsuranResource\Pages;

use App\Filament\Resources\AngsuranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAngsurans extends ListRecords
{
    protected static string $resource = AngsuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
