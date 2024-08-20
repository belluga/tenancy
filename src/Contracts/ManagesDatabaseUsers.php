<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Contracts;

use Belluga\Tenancy\DatabaseConfig;

interface ManagesDatabaseUsers extends TenantDatabaseManager
{
    public function createUser(DatabaseConfig $databaseConfig): bool;

    public function deleteUser(DatabaseConfig $databaseConfig): bool;

    public function userExists(string $username): bool;
}
