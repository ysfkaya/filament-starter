<?php

namespace Ysfkaya\Menu;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;

class MenuRenderer implements Htmlable
{
    /**
     * @var Menu
     */
    public $menu;

    /**
     * @var string
     */
    public $group;

    /**
     * @var string
     */
    public $view;

    /**
     * @var string
     */
    public $dataKey;

    /**
     * Menu renderer constructor
     *
     * @param  Menu  $menu
     * @param  string  $group
     * @param  string  $view
     */
    public function __construct(Menu $menu, string $group, string $view, string $dataKey = 'tree')
    {
        $this->menu = $menu;
        $this->group = $group;
        $this->view = $view;
        $this->dataKey = $dataKey;
    }

    /**
     * Render menu tree content
     *
     * @return string
     *
     * @throws \Throwable
     */
    public function render()
    {
        $tree = Cache::tags('menu')->remember(sprintf('menu::%s', $this->group), now()->addHours(1), function () {
            return $this->menu->withQueryRelations(['media'])->getTreeByGroup($this->group)->get();
        });

        return view($this->view, [$this->dataKey => $tree])->render();
    }

    /**
     * Get content as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->render();
    }
}
