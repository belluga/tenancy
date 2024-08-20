<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

/**
 * @see \Belluga\Tenancy\Database\Models\Tenant
 *
 * @method __call(string $method, array $parameters) IDE support. This will be a model.
 * @method static __callStatic(string $method, array $parameters) IDE support. This will be a model.
 * @mixin \MongoDB\Laravel\Eloquent\Model
 */
interface Tenant
{
    /** Get the name of the key used for identifying the tenant. */
    public function getTenantKeyName(): string;

    /** Get the value of the key used for identifying the tenant. */
    public function getTenantKey();

    /** Get the value of an internal key. */
    public function getInternal(string $key);

    /** Set the value of an internal key. */
    public function setInternal(string $key, $value);

    /** Run a callback in this tenant's environment. */
    public function run(callable $callback);
}
