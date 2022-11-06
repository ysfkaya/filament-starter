<?php

namespace Ysfkaya\Menu;

use App\Attributes\MenuLocale;
use App\Attributes\MenuTarget;
use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

class MenuItem extends DataTransferObject implements Arrayable
{
    public int|string|null $id = null;

    public string $title;

    public string $url;

    #[MenuTarget]
    public string $target = '_self';

    public string|null $group = null;

    #[MenuLocale]
    public string|null $locale = null;

    public MenuItemCollection $children;

    public function __construct(...$args)
    {
        $this->mapLocale(...$args)->mapChildren(...$args);

        parent::__construct(...$args);
    }

    protected function mapLocale(&$args): self
    {
        if (is_array($args) && ! isset($args['locale'])) {
            $args['locale'] = app()->getLocale();
        }

        return $this;
    }

    protected function mapChildren(&$args): self
    {
        if (is_array($args) && isset($args['children'])) {
            $args['children'] = MenuItemCollection::make($args['children']);
        } else {
            $args['children'] = MenuItemCollection::make();
        }

        return $this;
    }

    protected function parseArray(array $array): array
    {
        $result = parent::parseArray($array);

        $result['children'] = json_decode(json_encode($result['children']), true);

        return $result;
    }
}
