<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use MongoDB\Laravel\Eloquent\Model;
use Belluga\Tenancy\Resolvers;
use Belluga\Tenancy\Resolvers\Contracts\CachedTenantResolver;

/**
 * Meant to be used on models that belong to tenants.
 */
trait InvalidatesTenantsResolverCache
{
    public static $resolvers = [
        Resolvers\DomainTenantResolver::class,
        Resolvers\PathTenantResolver::class,
        Resolvers\RequestDataTenantResolver::class,
    ];

    public static function bootInvalidatesTenantsResolverCache()
    {
        static::saved(function (Model $model) {
            foreach (static::$resolvers as $resolver) {
                /** @var CachedTenantResolver $resolver */
                $resolver = app($resolver);

                $resolver->invalidateCache($model->tenant);
            }
        });
    }
}
