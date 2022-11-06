<?php

namespace Ysfkaya\Menu;

use Illuminate\Cache\Repository;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Ysfkaya\Menu\Http\Requests\MenuRequest;

/**
 * Class Menu.
 */
class Menu
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $resources;

    protected $withQueryRelations;

    /**
     * The menu class.
     *
     * @param  array  $config
     */
    public function __construct(protected array $config, protected Repository $cache, protected MenuRepository $repository)
    {
        $this->resources = collect();

        $this->initalize();
    }

    /**
     * Render the menu by group with view
     *
     * @param  string  $group
     * @param  string  $view
     * @return MenuRenderer
     */
    public function render(string $group, string $view)
    {
        return new MenuRenderer($this, $group, $view);
    }

    public function withQueryRelations(array $with)
    {
        $this->withQueryRelations = $with;

        return $this;
    }

    public function treeQuery()
    {
        $query = $this->repository->query();

        if ($this->withQueryRelations) {
            $query->with($this->withQueryRelations);
        }

        return $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getResources()
    {
        return $this->resources->values();
    }

    /**
     * @return self
     */
    public function addResource(Menuable $menuable)
    {
        $this->resources->push($menuable);

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getGroups()
    {
        $groups = $this->config['groups'] ?? [];

        return array_map(fn ($group) => __($group), $groups);
    }

    /**
     * @return mixed|string
     */
    public function getDefaultGroup()
    {
        return $this->config['default_group'] ?? 'main';
    }

    /**
     * @return array|mixed
     */
    public function getLocales()
    {
        return $this->config['locales'] ?? [];
    }

    public function getDepthByGroup($group)
    {
        return intval(($this->config['max_depth'] ?? [])[$group] ?? 0);
    }

    public function create(MenuItemCollection|array $items, bool $transform = true)
    {
        $items = is_array($items) ? MenuItemCollection::make($items, $transform) : $items;

        return $this->repository->create($items);
    }

    public function createFromRequest(MenuRequest $request)
    {
        return $this->repository->createFromRequest($request);
    }

    public function update(MenuRequest $request)
    {
        return $this->repository->update($request);
    }

    /**
     * @return MenuTree
     */
    public function getTree($query)
    {
        $result = $this->withCache(fn () => $query->get());

        return new MenuTree($result);
    }

    public function getTreeByGroup($group)
    {
        return $this->getTree(
            $this->treeQuery()->whereGroup($group)
        );
    }

    public function getTreeByGroupAndLocale($group, $locale)
    {
        return $this->getTree(
            $this->treeQuery()->whereGroup($group)->whereLocale($locale)
        );
    }

    public function getTreeByGroupAndCurrentLocale($group)
    {
        return $this->getTree(
            $this->treeQuery()->whereGroup($group)->whereLocale(app()->getLocale())
        );
    }

    public function getTreeByLocale($locale)
    {
        return $this->getTree(
            $this->treeQuery()->whereLocale($locale)
        );
    }

    public function getTreeByCurrentLocale()
    {
        return $this->getTreeByLocale(app()->getLocale());
    }

    /**
     * Load menu items from given path in config file.
     *
     * @throws \ReflectionException
     */
    protected function initalize()
    {
        foreach ($this->paths() as $namespace => $directory) {
            if (! is_dir($directory)) {
                continue;
            }

            foreach ((new Finder)->in($directory)->files()->depth(0) as $file) {
                $item = $namespace.str_replace(['/', '.php'], [
                    '\\',
                    '',
                ], Str::after($file->getPathname(), $file->getPath()));

                if ($this->items->offsetExists($item)) {
                    continue;
                }

                $class = new ReflectionClass($item);

                if ($this->isClassMenuable($class)) {
                    continue;
                }
            }
        }
    }

    /**
     * @return void
     */
    public function clearCache($key): void
    {
        if ($this->cache->has($cacheKey = $this->buildCacheKey($key)) && $this->config['cache']['enabled']) {
            $this->cache->forget($cacheKey);
        }
    }

    /**
     * @return mixed
     */
    protected function withCache($callback)
    {
        if (! $this->config['cache']['enabled']) {
            return $callback();
        }

        return $this->cache->remember($this->buildCacheKey(), now()->addMinutes($this->config['cache']['minutes']), $callback);
    }

    /**
     * @return string
     */
    protected function buildCacheKey()
    {
        $key = 'ss';

        return sprintf('%s::%s', $this->config['cache']['key'], $key);
    }

    /**
     * @return mixed
     */
    protected function paths()
    {
        if (! is_array($paths = $this->config['paths'])) {
            return [];
        }

        return collect($paths)->filter(function ($path, $namespace) {
            return is_string($namespace);
        })->toArray();
    }

    /**
     * @param  ReflectionClass  $class
     * @return bool
     */
    protected function isClassMenuable(ReflectionClass $class)
    {
        return $class->isAbstract() || $class->isInterface() || $class->isTrait() || ! $class->implementsInterface(Menuable::class);
    }
}
