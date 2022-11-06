<?php

namespace App\Providers;

use App\Models;
use App\Overrides\Spatie\MissingPageRouter;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Vite;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Saade\FilamentLaravelLog\Pages\ViewLog;
use Spatie\MissingPageRedirector\MissingPageRouter as SpatieMissingPageRouter;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Resources\PageResource;
use Spatie\MissingPageRedirector\Redirector\Redirector;

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
        $this->app->extend(SpatieMissingPageRouter::class, function ($instance, $app) {
            $router = new Router($app['events'], $app);

            $redirector = $app->make(Redirector::class);

            return new MissingPageRouter($router, $redirector);
        });
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
        $this->bootRelationMorphMap();
    }

    protected function bootRelationMorphMap()
    {
        Relation::enforceMorphMap([
            'user' => Models\User::class,
            'admin' => Models\Admin::class,
            'post' => Models\Post::class,
            'post_category' => Models\PostCategory::class,
            'page' => Models\Page::class,
        ]);
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
