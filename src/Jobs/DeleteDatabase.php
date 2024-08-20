<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Belluga\Tenancy\Contracts\TenantWithDatabase;
use Belluga\Tenancy\Events\DatabaseDeleted;
use Belluga\Tenancy\Events\DeletingDatabase;

class DeleteDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var TenantWithDatabase */
    protected $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {
        event(new DeletingDatabase($this->tenant));

        $this->tenant->database()->manager()->deleteDatabase($this->tenant);

        event(new DatabaseDeleted($this->tenant));
    }
}
