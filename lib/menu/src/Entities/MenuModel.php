<?php

namespace Ysfkaya\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuModel extends Model implements HasMedia
{
    use NodeTrait, InteractsWithMedia;

    protected $table = 'menu';

    protected $fillable = [
        'related_type',
        'related_id',
        '_lft',
        '_rgt',
        'parent_id',
        'target',
        'locale',
        'group',
        'title',
        'url',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * The root item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function root()
    {
        return $this->morphTo('root', 'related_type', 'related_id')->withDefault([
            'label' => __('Custom Links'),
            'singularLabel' => __('Custom Link'),
        ]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->singleFile();
    }
}
