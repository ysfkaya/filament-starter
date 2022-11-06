<?php

namespace Ysfkaya\Menu;

use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Kalnoy\Nestedset\Collection as TreeCollection;
use Ysfkaya\Menu\Http\Resources\MenuJsonResource;

/**
 * @extends TreeCollection
 */
class MenuTree implements Jsonable, JsonSerializable
{
    protected bool $wrapping = false;

    protected bool $usesJsonResource = false;

    protected TreeCollection $tree;

    public function __construct(protected TreeCollection $collection)
    {
        $this->tree = $collection->toTree();
    }

    public function wrapByGroup()
    {
        $this->wrapping = true;

        return $this;
    }

    public function useJsonResource()
    {
        $this->usesJsonResource = true;

        return $this;
    }

    public function get()
    {
        if ($this->wrapping) {
            return $this->wrapped();
        }

        $result = $this->tree;

        return $this->usesJsonResource ? MenuJsonResource::collection($result) : $result;
    }

    protected function wrapped()
    {
        return $this->tree->groupBy('group')->map(function ($items, $group) {
            return [
                'max_depth' => config("menu.max_depth.{$group}", 1),
                'items' => $this->usesJsonResource ? MenuJsonResource::collection($items) : $items,
            ];
        });
    }

    public function toJson($options = 0)
    {
        return json_encode($this->get(), $options);
    }

    public function jsonSerialize(): mixed
    {
        return $this->get()->jsonSerialize();
    }

    public function __call($method, $parameters)
    {
        $this->collection->$method(...$parameters);

        return $this;
    }
}
