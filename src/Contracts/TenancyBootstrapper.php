<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

/**
 * TenancyBootstrappers are classes that make your application tenant-aware automatically.
 */
interface TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant);

    public function revert();
}
