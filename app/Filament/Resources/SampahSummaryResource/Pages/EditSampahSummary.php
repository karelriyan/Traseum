<?php

namespace App\Filament\Resources\SampahSummaryResource\Pages;

use App\Filament\Resources\SampahSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSampahSummary extends EditRecord
{
    protected static string $resource = SampahSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
