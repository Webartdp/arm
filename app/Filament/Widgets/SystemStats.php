<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use App\Models\VerificationLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemStats extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalDocuments = Document::query()->count();

        $activeDocuments = Document::query()
            ->where('status', 'active')
            ->where(function ($query) {
                $query
                    ->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', today());
            })
            ->count();

        $revokedDocuments = Document::query()
            ->where('status', 'revoked')
            ->count();

        $todayChecks = VerificationLog::query()
            ->whereDate('created_at', today())
            ->count();

        return [
            Stat::make('Всего документов', $totalDocuments),
            Stat::make('Действующих', $activeDocuments)
                ->color('success'),
            Stat::make('Аннулированных', $revokedDocuments)
                ->color('danger'),
            Stat::make('Проверок сегодня', $todayChecks)
                ->color('info'),
        ];
    }
}
