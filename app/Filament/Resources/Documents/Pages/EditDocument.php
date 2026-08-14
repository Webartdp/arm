<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use App\Jobs\ProcessDocumentUpload;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reprocess')
                ->label('Распознать заново')
                ->icon('heroicon-o-arrow-path')
                ->visible(fn (): bool => filled($this->record->file_path))
                ->action(function (): void {
                    $this->record->forceFill([
                        'processing_status' => 'queued',
                        'processing_error' => null,
                    ])->saveQuietly();

                    ProcessDocumentUpload::dispatch($this->record->getKey())->afterCommit();

                    Notification::make()
                        ->title('Документ отправлен на распознавание')
                        ->body('Обновите страницу через несколько секунд, чтобы увидеть распознанные данные.')
                        ->success()
                        ->send();
                }),

            DeleteAction::make(),
        ];
    }
}
