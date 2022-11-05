<x-filament::widget>
    <x-filament::card class="relative">
        <div class="relative h-12 flex flex-col justify-center items-center space-y-2">
            <div class="space-y-1">
                <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" @class([
                    'flex items-end space-x-2 rtl:space-x-reverse text-gray-800 hover:text-primary-500 transition',
                    'dark:text-primary-500 dark:hover:text-primary-400' => config(
                        'filament.dark_mode'
                    ),
                ])>
                    <x-filament::brand />
                </a>
            </div>

            <div class="text-sm flex">
                <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer"
                    @class([
                        'text-gray-600 hover:text-primary-500 focus:outline-none focus:underline',
                        'dark:text-gray-300 dark:hover:text-primary-500' => config(
                            'filament.dark_mode'
                        ),
                    ])>

                    {{ str(config('app.url'))->after('://') }}
                </a>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
