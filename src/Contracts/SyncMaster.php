<?php

declare(strict_types=1);

namespace Stancl\Tenancy\Contracts;

use MongoDB\Laravel\Eloquent\Collection;
use MongoDB\Laravel\Relations\BelongsToMany;

/**
 * @property-read Tenant[]|Collection $tenants
 */
interface SyncMaster extends Syncable
{
    public function tenants(): BelongsToMany;

    public function getTenantModelName(): string;
}
