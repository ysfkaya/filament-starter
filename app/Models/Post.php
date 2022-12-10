<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kolossal\Multiplex\HasMeta;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property-read array|null $collections
 */
class Post extends Model implements HasMedia, Sortable
{
    use HasFactory, InteractsWithMedia, HasSlug, SortableTrait, HasMeta, QueryCacheable;

    protected static $flushCacheOnUpdate = true;

    public $cacheFor = 3600;

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function scopePublished(Builder $query)
    {
        return $query->where(fn ($q) => $q->whereNotNull('published_at')->orWhere('published_at', '>=', now()));
    }

    public function getUrlAttribute()
    {
        return route('post', $this->slug);
    }

    public function summary(int $limit = 25)
    {
        return summary_data($this, 'body', limit:$limit);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
    }
}
