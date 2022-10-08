<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Filament::serving(function () {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css', 'filament-build'),
            );

            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('filament.pages.profile')),
            ]);
        });

        Model::preventLazyLoading(! $this->app->environment('production'));

        $this->registerBladeDirectives();
    }

    protected function registerBladeDirectives(): void
    {
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
