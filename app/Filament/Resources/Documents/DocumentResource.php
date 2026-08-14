<?php

namespace App\Filament\Resources\Documents;

use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\Pages\ListDocuments;
use App\Models\Document;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $recordTitleAttribute = 'tracking_number';

    public static function getNavigationLabel(): string
    {
        return 'Документы';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Верификация';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Document::query()->count();
    }

    public static function getModelLabel(): string
    {
        return 'документ';
    }

    public static function getPluralModelLabel(): string
    {
        return 'документы';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Документ')
                    ->description('Загрузите PDF, изображение или DOCX и сохраните. Всё остальное система сделает автоматически: создаст код проверки, распознает данные, определит тип документа, подготовит скачивание и QR-код.')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Загрузить документ')
                            ->disk('local')
                            ->directory('documents')
                            ->visibility('private')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ])
                            ->maxSize(20480)
                            ->previewable(false)
                            ->downloadable()
                            ->preventFilePathTampering()
                            ->required()
                            ->helperText('PDF/JPG/PNG/WEBP/DOCX, до 20 МБ. После сохранения распознавание запускается автоматически.'),
                    ]),

                Section::make('Готовый результат')
                    ->description('Никакие данные документа руками заполнять не нужно.')
                    ->schema([
                        View::make('filament.documents.automation-status'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_number')
                    ->label('Код')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('processing_status')
                    ->label('Распознавание')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'queued' => 'В очереди',
                        'processing' => 'Распознаётся',
                        'processed' => 'Готово',
                        'failed' => 'Ошибка',
                        default => 'Не запускалось',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'processed' => 'success',
                        'processing', 'queued' => 'info',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('document_kind')
                    ->label('Тип результата')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'birth_certificate' => 'Свидетельство о рождении',
                        default => 'Документ',
                    })
                    ->toggleable(),

                TextColumn::make('document_type')
                    ->label('Распознанный тип')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('effective_status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Черновик',
                        'active' => 'Действителен',
                        'revoked' => 'Аннулирован',
                        'expired' => 'Истёк',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'revoked' => 'danger',
                        'expired' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('processed_at')
                    ->label('Обработан')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'draft' => 'Черновик',
                        'active' => 'Действителен',
                        'revoked' => 'Аннулирован',
                        'expired' => 'Истёк',
                    ]),
                SelectFilter::make('processing_status')
                    ->label('Распознавание')
                    ->options([
                        'queued' => 'В очереди',
                        'processing' => 'Распознаётся',
                        'processed' => 'Готово',
                        'failed' => 'Ошибка',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}
