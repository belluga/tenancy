<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\Tenancy\Database\ParentModelScope;

trait BelongsToPrimaryModel
{
    abstract public function getRelationshipToPrimaryModel(): string;

    public static function bootBelongsToPrimaryModel()
    {
        static::addGlobalScope(new ParentModelScope);
    }
}
