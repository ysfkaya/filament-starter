<?php

namespace  Ysfkaya\FilamentNotification\Actions\Concerns;

use Ysfkaya\FilamentNotification\Http\Livewire\NotificationFeed;

trait BelongsToLivewire
{
    protected NotificationFeed $livewire;

    public function livewire(NotificationFeed $livewire): static
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function getLivewire(): NotificationFeed
    {
        return $this->livewire;
    }
}
