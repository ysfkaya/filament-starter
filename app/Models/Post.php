<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;

/**
 * @property-read array|null $collections
 */
class Post extends Model implements HasMedia, Sortable
{
    use HasFactory, InteractsWithMedia, HasSlug, VirtualColumn, SortableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

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

    public function summary($limit = 40)
    {
        $content = preg_replace('/<h[1-6]>.*?<\/h[1-6]>/', '', $this->body);

        // Replace all within {} with nothing
        $content = preg_replace('/{.*?}/', '', $content);

        return str($content)->stripTags()->words($limit)->squish();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->singleFile();
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'category_id',
            'title',
            'slug',
            'body',
            'order_column',
            'published_at',
            'created_at',
            'updated_at',
        ];
    }
}
