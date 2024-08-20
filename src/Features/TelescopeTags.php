<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Features;

use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Belluga\Tenancy\Contracts\Feature;
use Belluga\Tenancy\Tenancy;

class TelescopeTags implements Feature
{
    public function bootstrap(Tenancy $tenancy): void
    {
        if (! class_exists(Telescope::class)) {
            return;
        }

        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];

            if (! request()->route()) {
                return $tags;
            }

            if (tenancy()->initialized) {
                $tags = [
                    'tenant:' . tenant('id'),
                ];
            }

            return $tags;
        });
    }
}
