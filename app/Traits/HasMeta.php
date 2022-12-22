<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Kolossal\Multiplex\HasMeta as BaseHasMeta;

trait HasMeta
{
    use BaseHasMeta;

    /**
     * @override
     *
     * Determine if model table has a given column.
     *
     * @param  string  $column
     * @return bool
     */
    public function hasColumn($column): bool
    {
        $class = get_class($this);

        if (! isset(static::$metaSchemaColumnsCache[$class])) {
            $table = $this->getConnection()->getTablePrefix().$this->getTable();

            // Cache the column names for the table
            $columns = Cache::tags('schema')->rememberForever('columns:'.$table, fn () => $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()));

            static::$metaSchemaColumnsCache[$class] = collect($columns)->map(fn ($item) => strtolower($item))->toArray();
        }

        return in_array(strtolower($column), static::$metaSchemaColumnsCache[$class]);
    }
}
