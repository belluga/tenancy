<?php

declare(strict_types=1);

namespace Belluga\Tenancy\TenantDatabaseManagers;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Belluga\Tenancy\Contracts\TenantDatabaseManager;
use Belluga\Tenancy\Contracts\TenantWithDatabase;
use Belluga\Tenancy\Exceptions\NoConnectionSetException;

class MongodbDatabaseManager implements TenantDatabaseManager
{
    /** @var string */
    protected $connection;

    protected function database(): Connection
    {
        if ($this->connection === null) {
            throw new NoConnectionSetException(static::class);
        }

        return DB::connection($this->connection);
    }

    public function setConnection(string $connection): void
    {
        $this->connection = $connection;
    }

    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        $database = $tenant->database()->getName();

        return $this->database()->create($database);
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        return $this->database()->dropIfExists($tenant->database()->getName());
    }

    public function databaseExists(string $name): bool
    {
        return true;
        // return (bool) $this->database()->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$name'");
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        $baseConfig['database'] = $databaseName;

        return $baseConfig;
    }
}
