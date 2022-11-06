<?php

namespace Ysfkaya\Menu;

/**
 * Interface Menuable.
 */
interface Menuable
{
    /**
     * Set menu label to display on title section bar.
     *
     * @return string
     */
    public function menuLabel(): string;

    /**
     * Set menu singular label to display on item type of resource.
     *
     * @return string
     */
    public function menuSingularLabel(): string;

    /**
     * Set menu resources to list in menu item.
     *
     * @return array
     */
    public function menuItems(): MenuItemCollection;
}
