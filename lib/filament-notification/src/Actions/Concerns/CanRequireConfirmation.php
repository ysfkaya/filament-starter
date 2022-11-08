<?php

namespace Ysfkaya\FilamentNotification\Actions\Concerns;

use Closure;

trait CanRequireConfirmation
{
    protected bool | Closure $isConfirmationRequired = false;

    public function requiresConfirmation(bool | Closure $condition = true): static
    {
        $this->isConfirmationRequired = $condition;

        return $this;
    }

    public function isConfirmationRequired(): bool
    {
        return $this->evaluate($this->isConfirmationRequired);
    }
}
