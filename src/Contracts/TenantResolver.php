<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

interface TenantResolver
{
    /**
     * Resolve a tenant using some value.
     *
     * @throws TenantCouldNotBeIdentifiedException
     */
    public function resolve(...$args): Tenant;
}
