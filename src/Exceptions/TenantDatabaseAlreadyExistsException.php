<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Exceptions;

use Belluga\Tenancy\Contracts\TenantCannotBeCreatedException;

class TenantDatabaseAlreadyExistsException extends TenantCannotBeCreatedException
{
    /** @var string */
    protected $database;

    public function reason(): string
    {
        return "Database {$this->database} already exists.";
    }

    public function __construct(string $database)
    {
        $this->database = $database;

        parent::__construct();
    }
}
