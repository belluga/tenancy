<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

trait CentralConnection
{
    public function getConnectionName()
    {
        return config('tenancy.database.central_connection');
    }
}
