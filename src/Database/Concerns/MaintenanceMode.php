<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Concerns;

use Carbon\Carbon;

trait MaintenanceMode
{
    public function putDownForMaintenance($data = [])
    {
        $this->update(['maintenance_mode' => [
            'time' => $data['time'] ?? Carbon::now()->getTimestamp(),
            'message' => $data['message'] ?? null,
            'retry' => $data['retry'] ?? null,
            'allowed' => $data['allowed'] ?? [],
        ]]);
    }
}
