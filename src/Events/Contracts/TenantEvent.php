<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Events\Contracts;

use Illuminate\Queue\SerializesModels;
use Belluga\Tenancy\Contracts\Tenant;

abstract class TenantEvent
{
    use SerializesModels;

    /** @var Tenant */
    public $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
