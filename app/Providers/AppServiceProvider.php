<?php

namespace App\Providers;

use App\Models;
use App\Overrides\Spatie\MissingPageRouter;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Vite;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Kolossal\Multiplex\HasMeta;
use Saade\FilamentLaravelLog\Pages\ViewLog;
use Spatie\MissingPageRedirector\MissingPageRouter as SpatieMissingPageRouter;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Forms\Components\PageBuilder;
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
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::simple-default');

        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());
        Model::unguard();

        $this->bootFilamentServing();
        $this->bootBladeDirectives();
        $this->bootRelationMorphMap();

        $this->registerMacros();
    }

    protected function registerMacros()
    {
        ViewComponent::macro('usesMeta', function () {
            /** @var \Filament\Forms\Components\Field|\Filament\Tables\Columns\Column $instance */
            $instance = $this;

            if ($instance instanceof \Filament\Tables\Columns\Column) {
                return $instance->getStateUsing(function ($column, $record) {
                    if (blank($record)) {
                        return;
                    }

                    if (! in_array(HasMeta::class, class_uses_recursive($record))) {
                        return;
                    }

                    $meta = $record->pluckMeta();

                    $key = $column->getName();

                    return data_get($meta, $key);
                });
            }

            return $instance->afterStateHydrated(function ($component, $record) {
                if (blank($record)) {
                    return;
                }

                if (! in_array(HasMeta::class, class_uses_recursive($record))) {
                    return;
                }

                $meta = $record->pluckMeta();

                $key = $component->getName();

                $value = data_get($meta, $key);

                $component->state($value);
            });
        });
    }

    protected function bootRelationMorphMap()
    {
        Relation::enforceMorphMap([
            'user' => Models\User::class,
            'admin' => Models\Admin::class,
            'post' => Models\Post::class,
            'post_category' => Models\PostCategory::class,
            'page' => Models\Page::class,
            'form' => Models\Form::class,
        ]);
    }

    protected function bootFilamentServing(): void
    {
        Filament::serving(function () {
            PageBuilder::configureUsing(fn ($builder) => $builder->collapsible()->collapsed(fn ($context) => in_array($context, ['edit', 'view'])));

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
                \Filament\Forms\Components\Card::make([
                    \Filament\Forms\Components\DateTimePicker::make('published_at')
                    ->label('Publish Date')
                    ->rules(['nullable', 'date']),
                ]),
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

        Blade::directive('replace', function ($expression) {
            [$subject,$search,$replace] = array_map('trim', explode(',', $expression));

            return "<?php echo e(Str::replace($search,$replace,$subject)); ?>";
        });
    }
}
