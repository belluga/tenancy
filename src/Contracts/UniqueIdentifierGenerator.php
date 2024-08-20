<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

interface UniqueIdentifierGenerator
{
    /**
     * Generate a unique identifier.
     */
    public static function generate($resource): string;
}
