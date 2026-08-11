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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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
                Section::make('Основные данные')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tracking_number')
                            ->label('Код проверки')
                            ->placeholder('XXXX-XXXX-XXXX-XXXX')
                            ->helperText('Можно оставить пустым — уникальный код будет создан автоматически.')
                            ->maxLength(19)
                            ->unique(ignoreRecord: true)
                            ->rules([
                                'nullable',
                                'regex:/^[A-Za-z0-9]{4}(?:-[A-Za-z0-9]{4}){3}$/',
                            ]),

                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'draft' => 'Черновик',
                                'active' => 'Действителен',
                                'revoked' => 'Аннулирован',
                                'expired' => 'Истёк',
                            ])
                            ->default('active')
                            ->required()
                            ->native(false),

                        TextInput::make('document_type')
                            ->label('Тип документа')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Название документа')
                            ->maxLength(255),

                        TextInput::make('subject_name')
                            ->label('Владелец / получатель')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        DatePicker::make('issue_date')
                            ->label('Дата выдачи'),

                        DatePicker::make('valid_until')
                            ->label('Действителен до')
                            ->afterOrEqual('issue_date'),
                    ]),

                Section::make('Файл и служебная информация')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Оригинал документа')
                            ->disk('local')
                            ->directory('documents')
                            ->visibility('private')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(20480)
                            ->previewable(false)
                            ->downloadable()
                            ->preventFilePathTampering()
                            ->helperText('PDF/JPG/PNG/WEBP, до 20 МБ. Файл хранится в приватном storage.'),

                        FileUpload::make('download_archive_path')
                            ->label('ZIP-архив для публичного скачивания')
                            ->disk('local')
                            ->directory('document-archives')
                            ->visibility('private')
                            ->acceptedFileTypes([
                                'application/zip',
                                'application/x-zip-compressed',
                            ])
                            ->maxSize(51200)
                            ->previewable(false)
                            ->downloadable()
                            ->preventFilePathTampering()
                            ->helperText('Этот ZIP появится кнопкой скачивания только после успешной проверки активного документа. До 50 МБ.'),

                        Textarea::make('notes')
                            ->label('Внутренняя заметка')
                            ->rows(5)
                            ->helperText('Не отображается на публичной странице.'),
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

                TextColumn::make('document_type')
                    ->label('Тип')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('subject_name')
                    ->label('Владелец / получатель')
                    ->searchable()
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

                TextColumn::make('issue_date')
                    ->label('Выдан')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('valid_until')
                    ->label('До')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(),

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
