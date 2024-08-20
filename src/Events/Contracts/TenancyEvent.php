<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Events\Contracts;

use Belluga\Tenancy\Tenancy;

abstract class TenancyEvent
{
    /** @var Tenancy */
    public $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }
}
