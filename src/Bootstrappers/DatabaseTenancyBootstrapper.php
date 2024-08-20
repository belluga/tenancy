<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Bootstrappers;

use Belluga\Tenancy\Contracts\TenancyBootstrapper;
use Belluga\Tenancy\Contracts\Tenant;
use Belluga\Tenancy\Contracts\TenantWithDatabase;
use Belluga\Tenancy\Database\DatabaseManager;
use Belluga\Tenancy\Exceptions\TenantDatabaseDoesNotExistException;

class DatabaseTenancyBootstrapper implements TenancyBootstrapper
{
    /** @var DatabaseManager */
    protected $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    public function bootstrap(Tenant $tenant)
    {
        /** @var TenantWithDatabase $tenant */

        // Better debugging, but breaks cached lookup in prod
        if (app()->environment('local')) {
            $database = $tenant->database()->getName();
            if (! $tenant->database()->manager()->databaseExists($database)) {
                throw new TenantDatabaseDoesNotExistException($database);
            }
        }

        $this->database->connectToTenant($tenant);
    }

    public function revert()
    {
        $this->database->reconnectToCentral();
    }
}
