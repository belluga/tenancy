<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Events;

use Belluga\Tenancy\Contracts\Syncable;
use Belluga\Tenancy\Contracts\TenantWithDatabase;

class SyncedResourceChangedInForeignDatabase
{
    /** @var Syncable */
    public $model;

    /** @var TenantWithDatabase|null */
    public $tenant;

    public function __construct(Syncable $model, ?TenantWithDatabase $tenant)
    {
        $this->model = $model;
        $this->tenant = $tenant;
    }
}
