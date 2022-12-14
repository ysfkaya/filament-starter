<?php

namespace App\Models;

use App\Traits\QueryCacheable;
use Kolossal\Multiplex\Meta as BaseMeta;

class Meta extends BaseMeta
{
    use QueryCacheable;
}
