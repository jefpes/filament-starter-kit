<?php

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalTenants extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $stats[] = Stat::make(__('Total tenants'), Tenant::query()->count());

        $stats[] = Stat::make(__('Total in monthly fee'), 'R$ ' . number_format(Tenant::query()->sum('monthly_fee'), 2, ',', '.'));

        return $stats;
    }
}
