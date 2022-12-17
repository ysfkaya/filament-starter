<?php

namespace App\Query;

use DateTime;
use Rennokki\QueryCache\Query\Builder;

class CacheBuilder extends Builder
{
    /**
     * Generate the plain unique cache key for the query.
     *
     * @param  string  $method
     * @param  string|null  $id
     * @param  string|null  $appends
     * @return string
     */
    public function generatePlainCacheKey(string $method = 'get', string $id = null, string $appends = null): string
    {
        // @phpstan-ignore-next-line
        $name = $this->connection->getName();

        $bindings = array_filter($this->getBindings(), function ($binding) {
            if ($binding instanceof DateTime) {
                // Ignore the real-time bindings.
                return ! now()->isSameUnit('minute', $binding);
            }

            return true;
        });

        // Count has no Sql, that's why it can't be used ->toSql()
        if ($method === 'count') {
            return $name.$method.$id.serialize($bindings).$appends;
        }

        return $name.$method.$id.$this->toSql().serialize($bindings).$appends;
    }
}
