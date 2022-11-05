<?php

namespace App\Filament\Widgets;

use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            $this->makeCard(model: User::class, label: 'Example 1', method: 'sum', column: 'id'),
            $this->makeCard(model: User::class, label: 'Example 2', method: 'count'),
            $this->makeCard(model: User::class, label: 'Example 3', method: 'count'),
            $this->makeCard(model: User::class, label: 'Example 4', method: 'count', range: 'daily', showChartAndDescription: false),
        ];
    }

    protected function makeCard(string $model, string $label, string $method, ?string $column = null, ?callable $formatValueUsing = null, string $range = 'monthly', bool $showChartAndDescription = true, $query = null)
    {
        $query = $query ? $query($model::query()) : $model::query();

        $trend = Cache::remember(
            sprintf('trend::%s_%s_%s_%s', $model, $method, is_null($column) ? '*' : $column, $range),
            now()->addMinutes(10),
            fn () => $this->getTrend($query, $method, $column, $range)
        );

        $value = $this->getLastTrendValue($trend, $range);

        if ($formatValueUsing) {
            $value = $formatValueUsing($value);
        }

        $value = $this->formatValue($value);

        $card = Card::make($label, $value);

        if ($showChartAndDescription) {
            $card->description($description = $this->getDifferenceTrendDescription($trend, $range))
                ->chart($this->getTrendValues($trend));

            if (str_contains($description, 'increase')) {
                $card->descriptionIcon('heroicon-s-trending-up')
                    ->color('success');
            } elseif (str_contains($description, 'decrease')) {
                $card->descriptionIcon('heroicon-s-trending-down')
                    ->color('danger');
            }
        }

        return $card;
    }

    protected function getTrend($query, $method, $column, $range)
    {
        $trend = Trend::query($query)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            );

        if ($range === 'daily') {
            $trend = $trend->perDay();
        } else {
            $trend = $trend->perMonth();
        }

        if ($method === 'count') {
            $column = '*';
        }

        return $trend->{$method}($column);
    }

    protected function getTrendValues(Collection $collection)
    {
        return $collection->map(function (TrendValue $item) {
            return $item->aggregate;
        })->toArray();
    }

    protected function formatValue($value)
    {
        if (! is_numeric($value)) {
            return $value;
        }

        $formattedValue = number_format($value, 2);

        // Remove whole number zeros
        $formattedValue = preg_replace('/\.00$/', '', $formattedValue);

        return $formattedValue;
    }

    protected function getLastTrendValue(Collection $collection, $range)
    {
        return data_get($this->getCurrentTrend($collection, $range), 'aggregate');
    }

    protected function getPreviousTrendValue(Collection $collection, $range)
    {
        return data_get($this->getPreviousTrend($collection, $range), 'aggregate');
    }

    protected function getCurrentTrend(Collection $collection, $range)
    {
        if ($range === 'daily') {
            $filter = fn ($date) => today()->isSameDay($date);
        } else {
            $filter = fn ($date) => today()->isSameMonth($date);
        }

        return $collection->filter(function (TrendValue $item) use ($filter) {
            return $filter($item->date);
        })->first();
    }

    public function getPreviousTrend(Collection $collection, $range)
    {
        if ($range === 'daily') {
            $filter = fn ($date) => today()->subDay()->isSameDay($date);
        } else {
            $filter = fn ($date) => today()->subMonth()->isSameMonth($date);
        }

        return $collection->filter(function (TrendValue $item) use ($filter) {
            return $filter($item->date);
        })->first();
    }

    protected function getDifferenceTrendDescription(Collection $collection, $range)
    {
        $difference = $this->getDifferenceTrendValue($collection, $range);

        if ($difference === 'No previous value') {
            return $difference;
        }

        if ($difference > 0) {
            return sprintf('%.0f%% increase', $difference);
        } elseif ($difference < 0) {
            return sprintf('%.0f%% decrease', $difference);
        }

        return 'No change';
    }

    protected function getDifferenceTrendValue(Collection $collection, $range)
    {
        $last = (float) $this->getLastTrendValue($collection, $range);

        $previous = (float) $this->getPreviousTrendValue($collection, $range);

        $diff = $last - $previous;

        if ($previous == 0) {
            return 'No previous value';
        }

        return ($diff / $previous) * 100;
    }

    protected function formatMoney(float $value)
    {
        $money = price($value);

        return formatMoney($money);
    }
}
