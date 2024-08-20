<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\Tenancy\Exceptions\DomainOccupiedByOtherTenantException;

trait EnsuresDomainIsNotOccupied
{
    public static function bootEnsuresDomainIsNotOccupied()
    {
        static::saving(function ($self) {
            if ($domain = $self->newQuery()->where('domain', $self->domain)->first()) {
                if ($domain->getKey() !== $self->getKey()) {
                    throw new DomainOccupiedByOtherTenantException($self->domain);
                }
            }
        });
    }
}
