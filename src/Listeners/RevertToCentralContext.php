<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Listeners;

use Belluga\Tenancy\Events\RevertedToCentralContext;
use Belluga\Tenancy\Events\RevertingToCentralContext;
use Belluga\Tenancy\Events\TenancyEnded;

class RevertToCentralContext
{
    public function handle(TenancyEnded $event)
    {
        event(new RevertingToCentralContext($event->tenancy));

        foreach ($event->tenancy->getBootstrappers() as $bootstrapper) {
            $bootstrapper->revert();
        }

        event(new RevertedToCentralContext($event->tenancy));
    }
}
