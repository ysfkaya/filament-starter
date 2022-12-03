<?php

namespace Ysfkaya\FilamentNotification\Actions;

use Closure;

class ButtonAction extends Action
{
    use Concerns\CanBeOutlined;

    protected string $view = 'filament-notification::actions.button-action';

    protected string|Closure|null $iconPosition = null;

    public function iconPosition(string|Closure|null $position): static
    {
        $this->iconPosition = $position;

        return $this;
    }

    public function getIconPosition(): ?string
    {
        return $this->evaluate($this->iconPosition);
    }
}
