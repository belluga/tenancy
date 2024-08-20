<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Listeners;

use Belluga\Tenancy\Events\BootstrappingTenancy;
use Belluga\Tenancy\Events\TenancyBootstrapped;
use Belluga\Tenancy\Events\TenancyInitialized;

class BootstrapTenancy
{
    public function handle(TenancyInitialized $event)
    {
        event(new BootstrappingTenancy($event->tenancy));

        foreach ($event->tenancy->getBootstrappers() as $bootstrapper) {
            $bootstrapper->bootstrap($event->tenancy->tenant);
        }

        event(new TenancyBootstrapped($event->tenancy));
    }
}
