<?php

namespace Ysfkaya\FilamentNotification\Actions\Concerns;

use Closure;

trait CanOpenUrl
{
    protected bool|Closure $shouldOpenUrlInNewTab = false;

    protected string|Closure|null $url = null;

    public function openUrlInNewTab(bool|Closure $condition = true): static
    {
        $this->shouldOpenUrlInNewTab = $condition;

        return $this;
    }

    public function url(string|Closure|null $url, bool|Closure $shouldOpenInNewTab = false): static
    {
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->evaluate($this->url);
    }

    public function shouldOpenUrlInNewTab(): bool
    {
        return $this->evaluate($this->shouldOpenUrlInNewTab);
    }
}
