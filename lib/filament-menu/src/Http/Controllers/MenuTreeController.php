<?php

namespace Ysfkaya\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Ysfkaya\Menu\Menu;

class MenuTreeController
{
    public function __invoke(Request $request, Menu $menu)
    {
        $group = $request->get('group', config('menu.default_group', 'main'));
        $locale = $request->get('locale', app()->getLocale());

        return response()->json([
            'tree' => $menu->getTreeByGroupAndLocale($group, $locale)->useJsonResource()->load('root'),
            'depth' => $menu->getDepthByGroup($group),
        ]);
    }
}
