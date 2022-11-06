<?php

namespace Ysfkaya\Menu;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/menu.php' => config_path('menu.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_menu_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_menu_table.php'),
        ], 'migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/menu.php', 'menu');

        $this->app->singleton('menu.repository', function ($app) {
            return new MenuRepository(
                resolve($app['config']->get('menu.model'))
            );
        });

        $this->app->singleton('menu', function ($app) {
            $config = $app['config']->get('menu');

            return new Menu($config, $app['cache.store'], $app['menu.repository']);
        });

        $this->app->bind(Menu::class, 'menu');

        Blade::directive('menu', function ($expression) {
            return "<?php echo Menu::render($expression); ?>";
        });
    }
}
