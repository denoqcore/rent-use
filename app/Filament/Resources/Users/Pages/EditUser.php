<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetPlan')
                ->label('Reset to Starter')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->forceFill([
                        'plan'            => 'starter',
                        'plan_expires_at' => null,
                    ])->save();

                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),

            DeleteAction::make(),
        ];
    }
}
