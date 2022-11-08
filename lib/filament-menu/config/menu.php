<?php

use Ysfkaya\Menu\Entities\MenuModel;

return [

    /**
     * This package will examine all the classes in the analysis path you have defined and
     * will take the models that include "HasMenu" trait and resolve them automatically.
     *
     * @example
     *
     * ['App' => app_path('Menu')]
     */
    'paths' => [
        //
    ],

    /**
     * The menu model to retrive the tree.
     */
    'model' => MenuModel::class,

    /**
     * The default group of menu items.
     */
    'default_group' => 'main',

    /**
     * The group fields.
     */
    'groups' => [
        'main' => 'Main Menu',
    ],

    /**
     * The locale fields.
     */
    'locales' => [
        'en' => 'English',
    ],

    /**
     * Cache the menu tree.
     */
    'cache' => [
        'enabled' => false,
        'key' => 'laravel-menu',
        'minutes' => 720, // 12 hours
    ],

    /**
     * Max depths by group.
     *
     * @example
     *
     * ['main' => 2]
     */
    'max_depth' => [],
];
