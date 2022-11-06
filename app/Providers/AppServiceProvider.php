<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Saade\FilamentLaravelLog\Pages\ViewLog;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Resources\PageResource;

/**
 * @property \Illuminate\Foundation\Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        $this->bootFilamentServing();
        $this->bootBladeDirectives();
    }

    protected function bootTelescope()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    protected function bootFilamentServing(): void
    {
        Filament::serving(function () {
            ViewLog::can(function ($admin) {
                if (is_object($admin) && method_exists($admin, 'isDeveloper')) {
                    return $admin->isDeveloper();
                }

                return false;
            });

            Filament::registerTheme(
                app(Vite::class)('resources/css/filament-app.css', 'filament-build'),
            );

            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('filament.pages.profile')),
            ]);

            PageResource::navigationGroup('CMS');

            FilamentFabricator::registerSchemaSlot('sidebar.after', [
                \Filament\Forms\Components\DateTimePicker::make('published_at')
                ->label('Publish Date')
                ->rules(['nullable', 'date']),
            ]);
        });
    }

    protected function bootBladeDirectives(): void
    {
        Blade::anonymousComponentNamespace('filament.components.menu', 'menu');

        Blade::directive('data', function ($expression) {
            return "<?php echo e(data_get_sequence($expression)); ?>";
        });

        Blade::directive('raw_data', function ($expression) {
            return "<?php echo data_get_sequence($expression); ?>";
        });

        Blade::directive('summary_data', function ($expression) {
            return "<?php echo summary_data($expression); ?>";
        });

        Blade::directive('user', function ($expression) {
            return "<?php echo data_get(auth()->user(),$expression); ?>";
        });
    }
}
