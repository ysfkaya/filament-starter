<?php

namespace Ysfkaya\FilamentNotification;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Ysfkaya\FilamentNotification\Http\Livewire\NotificationFeed;

class FilamentNotificationProvider extends PluginServiceProvider
{
    public static string $name = 'filament-notification';

    public function packageBooted(): void
    {
        Livewire::component('filament-notification.feed', NotificationFeed::class);

        Filament::registerRenderHook(
            'global-search.end',
            fn (): string => Blade::render('@livewire(\'filament-notification.feed\')'),
        );
    }
}
