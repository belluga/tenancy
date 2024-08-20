<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Belluga\Tenancy\Contracts\TenantWithDatabase;
use Belluga\Tenancy\Database\DatabaseManager;
use Belluga\Tenancy\Events\CreatingDatabase;
use Belluga\Tenancy\Events\DatabaseCreated;

class CreateDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var TenantWithDatabase|Model */
    protected $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle(DatabaseManager $databaseManager)
    {
        event(new CreatingDatabase($this->tenant));

        // Terminate execution of this job & other jobs in the pipeline
        if ($this->tenant->getInternal('create_database') === false) {
            return false;
        }

        $this->tenant->database()->makeCredentials();
        $databaseManager->ensureTenantCanBeCreated($this->tenant);
        $this->tenant->database()->manager()->createDatabase($this->tenant);

        event(new DatabaseCreated($this->tenant));
    }
}
