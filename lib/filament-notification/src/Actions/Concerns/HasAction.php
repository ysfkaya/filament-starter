<?php

namespace Ysfkaya\FilamentNotification\Actions\Concerns;

use Closure;

trait HasAction
{
    protected Closure|string|null $action = null;

    public function action(Closure|string|null $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): ?Closure
    {
        $action = $this->action;

        if (is_string($action)) {
            $action = Closure::fromCallable([$this->getLivewire(), $action]);
        }

        return $action;
    }
}
