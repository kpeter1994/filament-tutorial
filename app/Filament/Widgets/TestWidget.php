<?php

namespace App\Filament\Widgets;

use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class TestWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New users', User::count())
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->description('New users that have joined')
                ->chart([1,2,3,2,5,2,7,4,9,10])
                ->color('success'),
             Stat::make('New users', User::count())
                 ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                 ->description('New users that have joined')
                 ->chart([1,2,3,2,5,2,7,4,9,10])
                 ->color('success')
        ];
    }
}
