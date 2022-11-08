<?php

namespace Ysfkaya\Menu\Http\Controllers;

use Ysfkaya\Menu\Menu;

class MenuOptionsController
{
    public function __invoke(Menu $menu)
    {
        return response()->json([
            'groups' => $menu->getGroups(),
            'default_group' => $menu->getDefaultGroup(),
            'locales' => $menu->getLocales(),
            'current_locale' => app()->getLocale(),
        ]);
    }
}
