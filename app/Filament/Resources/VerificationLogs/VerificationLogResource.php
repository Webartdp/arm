<?php

namespace App\Filament\Resources\VerificationLogs;

use App\Filament\Resources\VerificationLogs\Pages\ListVerificationLogs;
use App\Models\VerificationLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VerificationLogResource extends Resource
{
    protected static ?string $model = VerificationLog::class;

    public static function getNavigationLabel(): string
    {
        return 'История проверок';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Верификация';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) VerificationLog::query()
            ->whereDate('created_at', today())
            ->count();
    }

    public static function getModelLabel(): string
    {
        return 'проверка';
    }

    public static function getPluralModelLabel(): string
    {
        return 'история проверок';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),

                TextColumn::make('tracking_number')
                    ->label('Код')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('result')
                    ->label('Результат')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'valid' => 'Действителен',
                        'revoked' => 'Аннулирован',
                        'expired' => 'Истёк',
                        'draft' => 'Не опубликован',
                        'not_found' => 'Не найден',
                        'invalid' => 'Некорректный код',
                        'date_mismatch' => 'Дата не совпадает',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'revoked', 'not_found', 'invalid', 'date_mismatch' => 'danger',
                        'expired', 'draft' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('document.tracking_number')
                    ->label('Документ')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('locale')
                    ->label('Язык')
                    ->badge(),

                TextColumn::make('user_agent')
                    ->label('User-Agent')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('result')
                    ->label('Результат')
                    ->options([
                        'valid' => 'Действителен',
                        'revoked' => 'Аннулирован',
                        'expired' => 'Истёк',
                        'draft' => 'Не опубликован',
                        'not_found' => 'Не найден',
                        'invalid' => 'Некорректный код',
                        'date_mismatch' => 'Дата не совпадает',
                    ]),

                SelectFilter::make('locale')
                    ->label('Язык')
                    ->options([
                        'ru' => 'RU',
                        'en' => 'EN',
                        'am' => 'AM',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVerificationLogs::route('/'),
        ];
    }
}
