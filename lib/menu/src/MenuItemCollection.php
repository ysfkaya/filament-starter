<?php

namespace Ysfkaya\Menu;

use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template TValue of \Ysfkaya\Menu\MenuItem
 *
 * @extends \Illuminate\Support\Collection<TKey, TValue>
 */
class MenuItemCollection extends Collection
{
    public $original;

    /**
     * {@inheritdoc}
     */
    public function __construct($items = [], public bool $transformMenuItem = false)
    {
        if ($transformMenuItem) {
            $this->items = $this->transformMenuItem($items);
        } else {
            parent::__construct($items);
        }

        $this->original = $this->getArrayableItems($items);
    }

    /**
     * {@inheritdoc}
     */
    public static function make($items = [], $transform = true)
    {
        return new static($items, $transform);
    }

    protected function transformMenuItem($items)
    {
        $items = $this->getArrayableItems($items);

        foreach ($items as $key => $item) {
            if ($item instanceof MenuItem) {
                continue;
            }

            $items[$key] = new MenuItem((array) $item);
        }

        return $items;
    }
}
