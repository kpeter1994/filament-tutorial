<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TestChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Test Chart Widget';

    protected function getData(): array
    {

        $data = Trend::model(User::class)
            ->between(
                start: now()->subMonth(1),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Treatments',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];

        /*return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [300, 500, 100],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                ],
            ],
            'labels' => ['A', 'B', 'C'],
        ];*/
    }

    protected function getType(): string
    {
        return 'line';
    }
}
