<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Middleware;

use Belluga\Tenancy\Contracts\TenantCouldNotBeIdentifiedException;
use Belluga\Tenancy\Contracts\TenantResolver;
use Belluga\Tenancy\Tenancy;

abstract class IdentificationMiddleware
{
    /** @var callable */
    public static $onFail;

    /** @var Tenancy */
    protected $tenancy;

    /** @var TenantResolver */
    protected $resolver;

    public function initializeTenancy($request, $next, ...$resolverArguments)
    {
        try {
            $this->tenancy->initialize(
                $this->resolver->resolve(...$resolverArguments)
            );
        } catch (TenantCouldNotBeIdentifiedException $e) {
            $onFail = static::$onFail ?? function ($e) {
                throw $e;
            };

            return $onFail($e, $request, $next);
        }

        return $next($request);
    }
}
