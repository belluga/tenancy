<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database;

use MongoDB\Laravel\Collection;
use Belluga\Tenancy\Contracts\Tenant;

/**
 * @property Tenant[] $items
 * @method void __construct(Tenant[] $items = [])
 * @method Tenant[] toArray()
 * @method Tenant offsetGet($key)
 * @method Tenant first()
 */
class TenantCollection extends Collection
{
    public function runForEach(callable $callable): self
    {
        tenancy()->runForMultiple($this->items, $callable);

        return $this;
    }
}
