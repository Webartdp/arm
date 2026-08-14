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
                Section::make('Загрузить документ')
                    ->description('Основной сценарий: загрузите PDF, изображение или DOCX и сохраните запись. Код проверки, распознавание данных и QR создаются автоматически.')
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
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ])
                            ->maxSize(20480)
                            ->previewable(false)
                            ->downloadable()
                            ->preventFilePathTampering()
                            ->helperText('PDF/JPG/PNG/WEBP/DOCX, до 20 МБ. После сохранения файл автоматически отправляется на распознавание.'),

                        View::make('filament.documents.automation-status'),
                    ]),

                Section::make('Распознанные основные данные')
                    ->description('Система заполняет эти поля автоматически. Открывайте этот блок только если нужно что-то проверить или исправить руками.')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('tracking_number')
                            ->label('Код проверки')
                            ->placeholder('XXXX-XXXX-XXXX-XXXX')
                            ->helperText('Если оставить пустым, уникальный код создаётся автоматически при сохранении.')
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

                        Select::make('document_kind')
                            ->label('Шаблон результата проверки')
                            ->options([
                                'generic' => 'Обычный документ',
                                'birth_certificate' => 'Свидетельство о рождении',
                            ])
                            ->default('generic')
                            ->required()
                            ->native(false)
                            ->helperText('Определяется автоматически при распознавании.'),

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

                Section::make('Свидетельство о рождении — данные гражданина')
                    ->description('Заполняется OCR автоматически для свидетельств о рождении.')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('citizen_first_name')
                            ->label('Имя')
                            ->maxLength(255),
                        TextInput::make('citizen_patronymic')
                            ->label('Отчество')
                            ->maxLength(255),
                        TextInput::make('citizen_last_name')
                            ->label('Фамилия')
                            ->maxLength(255),
                        TextInput::make('citizen_nationality')
                            ->label('Национальность')
                            ->maxLength(255),
                        TextInput::make('citizen_citizenship')
                            ->label('Гражданство')
                            ->maxLength(255),
                        DatePicker::make('birth_date')
                            ->label('Дата рождения'),
                        TextInput::make('birth_place')
                            ->label('Место рождения')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Section::make('Свидетельство о рождении — родители')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('father_first_name')
                            ->label('Отец — имя')
                            ->maxLength(255),
                        TextInput::make('father_patronymic')
                            ->label('Отец — отчество')
                            ->maxLength(255),
                        TextInput::make('father_last_name')
                            ->label('Отец — фамилия')
                            ->maxLength(255),
                        TextInput::make('father_nationality')
                            ->label('Отец — национальность')
                            ->maxLength(255),
                        TextInput::make('mother_first_name')
                            ->label('Мать — имя')
                            ->maxLength(255),
                        TextInput::make('mother_patronymic')
                            ->label('Мать — отчество')
                            ->maxLength(255),
                        TextInput::make('mother_last_name')
                            ->label('Мать — фамилия')
                            ->maxLength(255),
                        TextInput::make('mother_nationality')
                            ->label('Мать — национальность')
                            ->maxLength(255),
                    ]),

                Section::make('Свидетельство о рождении — регистрационные данные')
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        DatePicker::make('registration_date')
                            ->label('Дата регистрации'),
                        TextInput::make('registration_number')
                            ->label('Номер регистрации')
                            ->maxLength(255),
                        TextInput::make('registration_authority')
                            ->label('Орган регистрации')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('certificate_number')
                            ->label('Номер свидетельства')
                            ->maxLength(255),
                    ]),

                Section::make('Служебная информация')
                    ->description('Необязательные поля. Для обычной работы их заполнять не нужно.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        FileUpload::make('download_archive_path')
                            ->label('Отдельный ZIP для публичного скачивания — необязательно')
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
                            ->helperText('Если ZIP не загружен, кнопка DOWNLOAD DOCUMENT скачает исходный PDF/JPG/PNG/WEBP/DOCX.'),

                        Textarea::make('notes')
                            ->label('Внутренняя заметка')
                            ->rows(5)
                            ->helperText('Не отображается на публичной странице.'),

                        Textarea::make('extracted_text')
                            ->label('Распознанный исходный текст')
                            ->rows(12)
                            ->disabled()
                            ->dehydrated(false),
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
                    ->label('Шаблон')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'birth_certificate' => 'Свидетельство о рождении',
                        default => 'Обычный',
                    })
                    ->toggleable(),

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
                SelectFilter::make('document_kind')
                    ->label('Шаблон')
                    ->options([
                        'generic' => 'Обычный документ',
                        'birth_certificate' => 'Свидетельство о рождении',
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
