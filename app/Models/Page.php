<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;
use Z3d0X\FilamentFabricator\Models\Page as BasePage;

/**
 * @property string $url
 */
class Page extends BasePage implements HasMedia
{
    use VirtualColumn, HasSlug, InteractsWithMedia;

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

    public function getUrlAttribute()
    {
        return url($this->slug);
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
            'published_at',
            'created_at',
            'updated_at',
        ];
    }
}
