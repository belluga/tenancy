<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\Tenancy\Contracts\TenantWithDatabase;
use Belluga\Tenancy\DatabaseConfig;

trait HasDatabase
{
    public function database(): DatabaseConfig
    {
        /** @var TenantWithDatabase $this */

        return new DatabaseConfig($this);
    }
}
