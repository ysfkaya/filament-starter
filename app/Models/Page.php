<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;
use Z3d0X\FilamentFabricator\Models\Page as BasePage;

class Page extends BasePage
{
    use VirtualColumn, HasSlug;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'parent_id',
            'title',
            'slug',
            'layout',
            'blocks',
            'created_at',
            'updated_at',
        ];
    }
}
