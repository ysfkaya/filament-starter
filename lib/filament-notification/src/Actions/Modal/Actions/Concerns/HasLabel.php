<?php

namespace Ysfkaya\FilamentNotification\Actions\Modal\Actions\Concerns;

use Illuminate\Support\Str;

trait HasLabel
{
    protected ?string $label = null;

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label ?? (string) Str::of($this->getName())
            ->before('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();
    }
}
