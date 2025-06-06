<?php

namespace App\Filament\Resources\ServiceRequestResource\Pages;

use App\Filament\Resources\ServiceRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceRequest extends CreateRecord
{
    protected static string $resource = ServiceRequestResource::class;
    protected function authorizeAccess(): void
    {
        if (auth()->user()->role === 'secretary') {
            abort(403, 'Unauthorized');
        }
    }
}
