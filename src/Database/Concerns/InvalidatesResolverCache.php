<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\Tenancy\Contracts\Tenant;
use Belluga\Tenancy\Resolvers;
use Belluga\Tenancy\Resolvers\Contracts\CachedTenantResolver;

trait InvalidatesResolverCache
{
    public static $resolvers = [
        Resolvers\DomainTenantResolver::class,
        Resolvers\PathTenantResolver::class,
        Resolvers\RequestDataTenantResolver::class,
    ];

    public static function bootInvalidatesResolverCache()
    {
        static::saved(function (Tenant $tenant) {
            foreach (static::$resolvers as $resolver) {
                /** @var CachedTenantResolver $resolver */
                $resolver = app($resolver);

                $resolver->invalidateCache($tenant);
            }
        });
    }
}
