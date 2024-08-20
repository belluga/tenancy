<?php

declare(strict_types=1);

namespace Belluga\Tenancy;

use Ramsey\Uuid\Uuid;
use Belluga\Tenancy\Contracts\UniqueIdentifierGenerator;

class UUIDGenerator implements UniqueIdentifierGenerator
{
    public static function generate($resource): string
    {
        return Uuid::uuid4()->toString();
    }
}
