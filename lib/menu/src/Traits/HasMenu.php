<?php

namespace Ysfkaya\Menu\Traits;

use Illuminate\Support\Str;
use Ysfkaya\Menu\Menu;

trait HasMenu
{
    public static function bootHasMenu()
    {
        static::created(function ($model) {
            self::clearMenuCache($model->menuCacheKey());
        });

        static::updated(function ($model) {
            self::clearMenuCache($model->menuCacheKey());
        });

        static::deleted(function ($model) {
            self::clearMenuCache($model->menuCacheKey());
        });
    }

    /**
     *  Get the menu relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function menu()
    {
        return $this->morphOne(get_called_class(), 'related');
    }

    /**
     * Set the menu item collapse state when menu page mounted
     *
     * @return bool
     */
    public function menuCollapsed(): bool
    {
        return false;
    }

    /**
     * Set menu resources to list in menu item
     *
     * @return array
     */
    public function menuResources(Menu $menu): array
    {
        // $resources = $menu->withCache($this->menuCacheKey(), function () use ($menu) {
        //     return $this->newQuery()->get();
        // });

        // return $resources->map(function ($resource) {
        //     return new MenuResource($resource->title, $resource->url, $resource->id, get_class($this));
        // })->toArray();
    }

    /**
     * Set menu label to display on title section bar
     *
     * @return string
     */
    public function menuLabel(): string
    {
        return Str::plural(Str::title(Str::snake(class_basename(get_called_class()), ' ')));
    }

    /**
     * Menu cache key
     *
     * @return string
     */
    public function menuCacheKey(): string
    {
        return Str::snake(Str::plural(class_basename(get_called_class())), '_');
    }

    /**
     * Set menu singular label to display on item type of resource
     *
     * @return string
     */
    public function menuSingularLabel(): string
    {
        return Str::singular($this->menuLabel());
    }

    /**
     * Set the model appends
     *
     * @return void
     */
    public function initializeHasMenu()
    {
        $this->setAppends([
            'label',
            'singularLabel',
            'collapsed',
        ]);
    }

    /**
     * Get the label attribute
     *
     * @return string
     */
    public function getLabelAttribute()
    {
        return $this->menuLabel();
    }

    /**
     * Get the singular label attribute
     *
     * @return string
     */
    public function getSingularLabelAttribute()
    {
        return $this->menuSingularLabel();
    }

    /**
     * Get the collapsed attribute
     *
     * @return bool
     */
    public function getCollapsedAttribute()
    {
        return $this->menuCollapsed();
    }

    /**
     * @return void
     */
    public static function clearMenuCache($key)
    {
        resolve(Menu::class)->clearCache($key);
    }
}
