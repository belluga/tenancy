<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Belluga\Tenancy\Contracts\TenantCouldNotBeIdentifiedException;

// todo: in v4 this should be suffixed with Exception
class TenantCouldNotBeIdentifiedById extends TenantCouldNotBeIdentifiedException implements ProvidesSolution
{
    public function __construct($tenant_id)
    {
        parent::__construct("Tenant could not be identified with tenant_id: $tenant_id");
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Tenant could not be identified with that ID')
            ->setSolutionDescription('Are you sure the ID is correct and the tenant exists?')
            ->setDocumentationLinks([
                'Initializing Tenants' => 'https://tenancyforlaravel.com/docs/v3/tenants',
            ]);
    }
}
