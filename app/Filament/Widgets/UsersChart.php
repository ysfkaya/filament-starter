<?php

namespace App\Filament\Widgets;

use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\Cache;

class UsersChart extends LineChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Users Per Month';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $trend = Cache::remember(
            sprintf('trend::%s_%s_%s', User::class, 'count', '*'),
            now()->addMinutes(30),
            fn () => Trend::query(User::query())->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )->perMonth()->count()
        );

        $data = $trend->map(fn ($value) => $value->aggregate)->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
}
