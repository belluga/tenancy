<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Belluga\Tenancy\Contracts\Syncable;
use Belluga\Tenancy\Contracts\UniqueIdentifierGenerator;
use Belluga\Tenancy\Events\SyncedResourceSaved;

trait ResourceSyncing
{
    public static function bootResourceSyncing()
    {
        static::saved(function (Syncable $model) {
            /** @var ResourceSyncing $model */
            $model->triggerSyncEvent();
        });

        static::creating(function (self $model) {
            if (! $model->getAttribute($model->getGlobalIdentifierKeyName()) && app()->bound(UniqueIdentifierGenerator::class)) {
                $model->setAttribute(
                    $model->getGlobalIdentifierKeyName(),
                    app(UniqueIdentifierGenerator::class)->generate($model)
                );
            }
        });
    }

    public function triggerSyncEvent()
    {
        /** @var Syncable $this */
        event(new SyncedResourceSaved($this, tenant()));
    }
}
