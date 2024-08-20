<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Events;

use MongoDB\Laravel\Eloquent\Model;
use Belluga\Tenancy\Contracts\Syncable;
use Belluga\Tenancy\Contracts\TenantWithDatabase;

class SyncedResourceSaved
{
    /** @var Syncable|Model */
    public $model;

    /** @var TenantWithDatabase|Model|null */
    public $tenant;

    public function __construct(Syncable $model, ?TenantWithDatabase $tenant)
    {
        $this->model = $model;
        $this->tenant = $tenant;
    }
}
