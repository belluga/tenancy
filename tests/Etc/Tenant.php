<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Tests\Etc;

use Belluga\Tenancy\Contracts\TenantWithDatabase;
use Belluga\Tenancy\Database\Concerns\HasDatabase;
use Belluga\Tenancy\Database\Concerns\HasDomains;
use Belluga\Tenancy\Database\Models;

class Tenant extends Models\Tenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
}
