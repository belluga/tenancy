<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

use Belluga\Tenancy\DatabaseConfig;

interface TenantWithDatabase extends Tenant
{
    public function database(): DatabaseConfig;

    /** Get an internal key. */
    public function getInternal(string $key);
}
