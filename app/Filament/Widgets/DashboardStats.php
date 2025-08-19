<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            Stat::make('Total Sales Today', 'ރ' . number_format(Invoice::whereDate('created_at', $today)->sum('total'), 2))
                ->description('Sales made today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Sales This Month', 'ރ' . number_format(Invoice::whereDate('created_at', '>=', $thisMonth)->sum('total'), 2))
                ->description('Sales this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Total Products', Product::count())
                ->description('Available products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),

            Stat::make('Total Customers', Customer::count())
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
