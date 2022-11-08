<?php

namespace Ysfkaya\Menu\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxDepthRule implements Rule
{
    /**
     * @var int
     */
    private $maxDepth;

    /**
     * @var array
     */
    private $level;

    public function __construct(private string|null $group)
    {
        $this->maxDepth = config('menu.max_depth.'.$group, 1);
        $this->level = [];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_null($this->group)) {
            return false;
        }

        return ! $this->hasAnyOverflow($value);
    }

    protected function hasAnyOverflow($tree): bool
    {
        if (is_null($this->maxDepth)) {
            return true;
        }

        $this->calculateMaxLevel($tree);

        return $this->getMaxLevel() > $this->maxDepth;
    }

    protected function calculateMaxLevel(array $tree): void
    {
        if (empty($tree)) {
            return;
        }

        foreach ($tree as $index => $item) {
            $this->level[$index] = $this->calculateChildrenLevel($item['children'] ?? [], 1);
        }
    }

    protected function calculateChildrenLevel(array $children, int $lastLevel)
    {
        if (empty($children)) {
            return $lastLevel;
        }

        $maxLevel = 1;

        foreach ($children as $child) {
            $maxLevel = $this->calculateChildrenLevel($child['children'] ?? [], $maxLevel);
        }

        return $maxLevel + 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.menu.max_depth', [
            'max' => $this->maxDepth,
            'current' => $this->getMaxLevel(),
        ]);
    }

    public function getMaxLevel()
    {
        if (empty($this->level)) {
            return 0;
        }

        return max(0, ...array_values($this->level));
    }
}
