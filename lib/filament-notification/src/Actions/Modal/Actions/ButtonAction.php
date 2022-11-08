<?php

namespace Ysfkaya\FilamentNotification\Actions\Modal\Actions;

class ButtonAction extends Action
{
    use Concerns\CanBeOutlined;
    use Concerns\HasIcon;

    protected string $view = 'filament-notification::actions.modal.actions.button-action';

    protected ?string $iconPosition = null;

    public function iconPosition(string $position): static
    {
        $this->iconPosition = $position;

        return $this;
    }

    public function getIconPosition(): ?string
    {
        return $this->iconPosition;
    }
}
