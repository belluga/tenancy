<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Models;

use MongoDB\Laravel\Relations\Pivot;
use Belluga\Tenancy\Contracts\Syncable;

class TenantPivot extends Pivot
{
    public static function boot()
    {
        parent::boot();

        static::saved(function (self $pivot) {
            $parent = $pivot->pivotParent;

            if ($parent instanceof Syncable) {
                $parent->triggerSyncEvent();
            }
        });
    }
}
