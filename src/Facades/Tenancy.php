<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Facades;

use Illuminate\Support\Facades\Facade;

class Tenancy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Belluga\Tenancy\Tenancy::class;
    }
}
