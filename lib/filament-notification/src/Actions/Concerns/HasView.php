<?php

namespace Ysfkaya\FilamentNotification\Actions\Concerns;

trait HasView
{
    protected string $view;

    public function getView(): string
    {
        return $this->view;
    }
}
