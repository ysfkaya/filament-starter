<?php

namespace Ysfkaya\Menu\Http\Controllers;

use Ysfkaya\Menu\Menu;
use Ysfkaya\Menu\Menuable;

class MenuResourcesController
{
    public function __invoke(Menu $menu)
    {
        return response()->json([
            'resources' => $menu->getResources()->map(function (Menuable $resource) {
                return [
                    'label' => $resource->menuLabel(),
                    'singularLabel' => $resource->menuSingularLabel(),
                    'items' => $resource->menuItems(),
                ];
            }),
        ]);
    }
}
