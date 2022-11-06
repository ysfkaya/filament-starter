<?php

namespace Ysfkaya\Menu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Kalnoy\Nestedset\QueryBuilder treeQuery()
 * @method static \Illuminate\Support\Collection getResources()
 * @method static \Ysfkaya\Menu\Menu addResource(\Ysfkaya\Menu\Menuable $menuable)
 * @method static \Ysfkaya\Menu\Menu withQueryRelations(array $with)
 * @method static array getGroups()
 * @method static string getDefaultGroup()
 * @method static array getLocales()
 * @method static int getDepthByGroup(string $group)
 * @method static void create(\Ysfkaya\Menu\MenuItemCollection\MenuItemCollection|array $items,bool $transform = true)
 * @method static void createFromRequest(\Ysfkaya\Menu\Http\Requests\MenuRequest $request)
 * @method static int update(\Ysfkaya\Menu\Http\Requests\MenuRequest $request)
 * @method static \Ysfkaya\Menu\MenuTree getTree(\Kalnoy\Nestedset\QueryBuilder $query)
 * @method static \Ysfkaya\Menu\MenuTree getTreeQuery(string $group)
 * @method static \Ysfkaya\Menu\MenuTree getTreeByGroupAndLocale(string $group,string $locale)
 * @method static \Ysfkaya\Menu\MenuTree getTreeByGroupAndCurrentLocale(string $group)
 * @method static \Ysfkaya\Menu\MenuTree getTreeByLocale(string $locale)
 * @method static \Ysfkaya\Menu\MenuTree getTreeByCurrentLocale()
 * @method static void clearCache($key)
 *
 * @extends \Ysfkaya\Menu\Menu
 */
class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu';
    }
}
