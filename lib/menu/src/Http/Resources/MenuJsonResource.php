<?php

namespace Ysfkaya\Menu\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuJsonResource extends JsonResource
{
    /**
     * {@inheritdoc}
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'target' => $this->target,
            'group' => $this->group,
            'locale' => $this->locale,
            'type' => $this->type,
            'exists' => $this->exists,
            'options' => $this->options,
            'root' => $this->whenLoaded('root'),
            'children' => $this->when(! empty($this->children), static::collection($this->children)),
        ];
    }
}
