<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

use Belluga\Tenancy\Tenancy;

/** Additional features, like Telescope tags and tenant redirects. */
interface Feature
{
    public function bootstrap(Tenancy $tenancy): void;
}
