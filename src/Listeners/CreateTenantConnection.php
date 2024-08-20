<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Listeners;

use Belluga\Tenancy\Database\DatabaseManager;
use Belluga\Tenancy\Events\Contracts\TenantEvent;

class CreateTenantConnection
{
    /** @var DatabaseManager */
    protected $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    public function handle(TenantEvent $event)
    {
        $this->database->createTenantConnection($event->tenant);
    }
}
