<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

trait TenantConnection
{
    public function getConnectionName()
    {
        return 'tenant';
    }
}
