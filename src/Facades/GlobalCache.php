<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Facades;

use Illuminate\Support\Facades\Cache;

class GlobalCache extends Cache
{
    protected static function getFacadeAccessor()
    {
        return 'globalCache';
    }
}
