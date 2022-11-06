<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HandlesMenuManager;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ysfkaya\Menu\Facades\Menu;

class MenuManager extends Page
{
    use HandlesMenuManager, HasPageShield;

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?string $title = 'Menu';

    protected static ?string $slug = 'cms/menu-manager';

    protected static ?string $navigationIcon = 'heroicon-o-menu';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.menu-manager';

    public $groups;

    public $selectedGroup;

    public $hasChanges;

    protected $queryString = [
        'selectedGroup' => ['except' => 'header', 'as' => 'group'],
    ];

    /**
     * @var Collection
     */
    public $tree;

    public function mount()
    {
        config([
            'filament.layout.max_content_width' => '4xl',
        ]);

        $this->groups = Menu::getGroups();

        $this->selectedGroup = request('group', Menu::getDefaultGroup());

        $this->loadTree();
    }

    public function updatedSelectedGroup()
    {
        $this->loadTree();

        $this->hasChanges = false;
    }

    protected function getSaveButtonColor()
    {
        return $this->hasChanges ? 'warning' : 'primary';
    }

    protected function mapWithUuid($item)
    {
        if (isset($item['children']) && count($item['children']) > 0) {
            $item['children'] = collect($item['children'])->mapWithKeys(fn ($item) => $this->mapWithUuid($item))->all();
        }

        $id = Str::uuid()->toString();

        return [
            $id => $item,
        ];
    }

    protected function loadTree()
    {
        $tree = Menu::getTreeByGroupAndCurrentLocale($this->selectedGroup);

        $this->tree = collect(json_decode($tree->useJsonResource()->toJson(), true))->mapWithKeys(fn ($item) => $this->mapWithUuid($item));
    }

    protected function getViewData(): array
    {
        return [
            'maxDepth' => config("menu.max_depth.{$this->selectedGroup}", 1),
        ];
    }
}
