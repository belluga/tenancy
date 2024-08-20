<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

/**
 * @property-read Tenant $tenant
 *
 * @see \Belluga\Tenancy\Database\Models\Domain
 *
 * @method __call(string $method, array $parameters) IDE support. This will be a model.
 * @method static __callStatic(string $method, array $parameters) IDE support. This will be a model.
 * @mixin \MongoDB\Laravel\Eloquent\Model
 */
interface Domain
{
    public function tenant();
}
