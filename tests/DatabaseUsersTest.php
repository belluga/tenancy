<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Belluga\JobPipeline\JobPipeline;
use Belluga\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper;
use Belluga\Tenancy\Contracts\ManagesDatabaseUsers;
use Belluga\Tenancy\Events\DatabaseCreated;
use Belluga\Tenancy\Events\TenancyInitialized;
use Belluga\Tenancy\Events\TenantCreated;
use Belluga\Tenancy\Exceptions\TenantDatabaseUserAlreadyExistsException;
use Belluga\Tenancy\Jobs\CreateDatabase;
use Belluga\Tenancy\Listeners\BootstrapTenancy;
use Belluga\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager;
use Belluga\Tenancy\TenantDatabaseManagers\PermissionControlledMySQLDatabaseManager;
use Belluga\Tenancy\Tests\Etc\Tenant;

class DatabaseUsersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config([
            'tenancy.database.managers.mysql' => PermissionControlledMySQLDatabaseManager::class,
            'tenancy.database.suffix' => '',
            'tenancy.database.template_tenant_connection' => 'mysql',
        ]);

        Event::listen(TenantCreated::class, JobPipeline::make([CreateDatabase::class])->send(function (TenantCreated $event) {
            return $event->tenant;
        })->toListener());
    }

    /** @test */
    public function users_are_created_when_permission_controlled_mysql_manager_is_used()
    {
        $tenant = new Tenant([
            'id' => 'foo' . Str::random(10),
        ]);
        $tenant->database()->makeCredentials();

        /** @var ManagesDatabaseUsers $manager */
        $manager = $tenant->database()->manager();
        $this->assertFalse($manager->userExists($tenant->database()->getUsername()));

        $tenant->save();

        $this->assertTrue($manager->userExists($tenant->database()->getUsername()));
    }

    /** @test */
    public function a_tenants_database_cannot_be_created_when_the_user_already_exists()
    {
        $username = 'foo' . Str::random(8);
        $tenant = Tenant::create([
            'tenancy_db_username' => $username,
        ]);

        /** @var ManagesDatabaseUsers $manager */
        $manager = $tenant->database()->manager();
        $this->assertTrue($manager->userExists($tenant->database()->getUsername()));
        $this->assertTrue($manager->databaseExists($tenant->database()->getName()));

        $this->expectException(TenantDatabaseUserAlreadyExistsException::class);
        Event::fake([DatabaseCreated::class]);

        $tenant2 = Tenant::create([
            'tenancy_db_username' => $username,
        ]);

        /** @var ManagesDatabaseUsers $manager */
        $manager2 = $tenant2->database()->manager();

        // database was not created because of DB transaction
        $this->assertFalse($manager2->databaseExists($tenant2->database()->getName()));
        Event::assertNotDispatched(DatabaseCreated::class);
    }

    /** @test */
    public function correct_grants_are_given_to_users()
    {
        PermissionControlledMySQLDatabaseManager::$grants = [
            'ALTER',
            'ALTER ROUTINE',
            'CREATE',
        ];

        $tenant = Tenant::create([
            'tenancy_db_username' => $user = 'user' . Str::random(8),
        ]);

        $query = DB::connection('mysql')->select("SHOW GRANTS FOR `{$tenant->database()->getUsername()}`@`%`")[1];
        $this->assertStringStartsWith('GRANT CREATE, ALTER, ALTER ROUTINE ON', $query->{"Grants for {$user}@%"}); // @mysql because that's the hostname within the docker network
    }

    /** @test */
    public function having_existing_databases_without_users_and_switching_to_permission_controlled_mysql_manager_doesnt_break_existing_dbs()
    {
        config([
            'tenancy.database.managers.mysql' => MySQLDatabaseManager::class,
            'tenancy.database.suffix' => '',
            'tenancy.database.template_tenant_connection' => 'mysql',
            'tenancy.bootstrappers' => [
                DatabaseTenancyBootstrapper::class,
            ],
        ]);

        Event::listen(TenancyInitialized::class, BootstrapTenancy::class);

        $tenant = Tenant::create([
            'id' => 'foo' . Str::random(10),
        ]);

        $this->assertTrue($tenant->database()->manager() instanceof MySQLDatabaseManager);

        tenancy()->initialize($tenant); // check if everything works
        tenancy()->end();

        config(['tenancy.database.managers.mysql' => PermissionControlledMySQLDatabaseManager::class]);

        tenancy()->initialize($tenant); // check if everything works

        $this->assertTrue($tenant->database()->manager() instanceof PermissionControlledMySQLDatabaseManager);
        $this->assertSame('root', config('database.connections.tenant.username'));
    }
}
