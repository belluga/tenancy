<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\VirtualColumn\VirtualColumn;

/**
 * Extends VirtualColumn for backwards compatibility. This trait will be removed in v4.
 */
trait HasDataColumn
{
    use VirtualColumn;
}
