@props([
    'chart' => null,
    'chartColor' => null,
    'color' => null,
    'icon' => null,
    'description' => null,
    'descriptionColor' => null,
    'descriptionIcon' => null,
    'flat' => false,
    'label' => null,
    'tag' => 'div',
    'value' => null,
    'extraAttributes' => [],
])

<{!! $tag !!}
    {{ $attributes->merge($extraAttributes)->class([
        'relative p-6 rounded-2xl bg-white shadow filament-stats-card',
        'dark:bg-gray-800' => config('filament.dark_mode'),
    ]) }}
>
    <div @class([
        'space-y-2',
    ])>
        <div @class([
            'flex items-center space-x-2 rtl:space-x-reverse text-sm font-medium text-gray-500',
            'dark:text-gray-200' => config('filament.dark_mode'),
        ])>
            @if ($icon)
                <x-dynamic-component :component="$icon" class="w-4 h-4" />
            @endif
            <span>{{ $label }}</span>
        </div>

        <div class="text-3xl">
            {{ $value }}
        </div>

        @if ($description)
            <div @class([
                'flex items-center space-x-1 rtl:space-x-reverse text-sm font-medium',
                match ($descriptionColor) {
                    'danger' => 'text-danger-600',
                    'primary' => 'text-primary-600',
                    'success' => 'text-success-600',
                    'warning' => 'text-warning-600',
                    default => 'text-gray-600',
                },
            ])>
                <span>{{ $description }}</span>

                @if ($descriptionIcon)
                    <x-dynamic-component :component="$descriptionIcon" class="w-4 h-4" />
                @endif
            </div>
        @endif
    </div>

    @if ($chart)
        <div class="absolute bottom-0 inset-x-0 rounded-b-2xl overflow-hidden">
            <canvas
                x-data="{
                    chart: null,

                    init: function () {
                        chart = new Chart(
                            $el,
                            {
                                type: 'line',
                                data: {
                                    labels: {{ json_encode(array_keys($chart)) }},
                                    datasets: [{
                                        data: {{ json_encode(array_values($chart)) }},
                                        backgroundColor: getComputedStyle($refs.backgroundColorElement).color,
                                        borderColor: getComputedStyle($refs.borderColorElement).color,
                                        borderWidth: 2,
                                        fill: 'start',
                                        tension: 0.5,
                                    }],
                                },
                                options: {
                                    elements: {
                                        point: {
                                            radius: 0,
                                        },
                                    },
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false,
                                        },
                                    },
                                    scales: {
                                        x:  {
                                            display: false,
                                        },
                                        y:  {
                                            display: false,
                                        },
                                    },
                                    tooltips: {
                                        enabled: false,
                                    },
                                },
                            }
                        )
                    },
                }"
                wire:ignore
                class="h-6"
            >
                <span
                    x-ref="backgroundColorElement"
                    @class([
                        match ($chartColor) {
                            'danger' => \Illuminate\Support\Arr::toCssClasses(['text-danger-50', 'dark:text-danger-700' => config('filament.dark_mode')]),
                            'primary' => \Illuminate\Support\Arr::toCssClasses(['text-primary-50', 'dark:text-primary-700' => config('filament.dark_mode')]),
                            'success' => \Illuminate\Support\Arr::toCssClasses(['text-success-50', 'dark:text-success-700' => config('filament.dark_mode')]),
                            'warning' => \Illuminate\Support\Arr::toCssClasses(['text-warning-50', 'dark:text-warning-700' => config('filament.dark_mode')]),
                            default => \Illuminate\Support\Arr::toCssClasses(['text-gray-50', 'dark:text-gray-700' => config('filament.dark_mode')]),
                        },
                    ])
                ></span>

                <span
                    x-ref="borderColorElement"
                    @class([
                        match ($chartColor) {
                            'danger' => 'text-danger-400',
                            'primary' => 'text-primary-400',
                            'success' => 'text-success-400',
                            'warning' => 'text-warning-400',
                            default => 'text-gray-400',
                        },
                    ])
                ></span>
            </canvas>
        </div>
    @endif
</{!! $tag !!}>
