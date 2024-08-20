<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\Tenancy\Contracts\Domain;

/**
 * @property-read Domain[]|\MongoDB\Laravel\Eloquent\Collection $domains
 */
trait HasDomains
{
    public function domains()
    {
        return $this->hasMany(config('tenancy.domain_model'), 'tenant_id');
    }

    public function createDomain($data): Domain
    {
        $class = config('tenancy.domain_model');

        if (! is_array($data)) {
            $data = ['domain' => $data];
        }

        $domain = (new $class)->fill($data);
        $domain->tenant()->associate($this);
        $domain->save();

        return $domain;
    }
}
