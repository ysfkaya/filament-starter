<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;
use Z3d0X\FilamentFabricator\Facades\FilamentFabricator;
use Z3d0X\FilamentFabricator\Models\Page as BasePage;

/**
 * @property string $url
 */
class Page extends BasePage implements HasMedia
{
    use VirtualColumn, HasSlug, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'blocks' => 'array',
        'parent_id' => 'integer',
        'published_at' => 'datetime',
    ];

    public function render()
    {
        $component = FilamentFabricator::getLayoutFromName($this->layout)::getComponent();

        return Blade::render(
            <<<'BLADE'
                <x-dynamic-component
                    :component="$component"
                    :page="$page"
                />
            BLADE,
            ['component' => $component, 'page' => $this]
        );
    }

    public function scopePublished(Builder $builder)
    {
        return $builder->whereNotNull('published_at')->where('published_at', '<=', now());
    }

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
