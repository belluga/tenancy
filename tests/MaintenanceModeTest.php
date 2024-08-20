<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Tests;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Support\Facades\Route;
use Belluga\Tenancy\Database\Concerns\MaintenanceMode;
use Belluga\Tenancy\Middleware\CheckTenantForMaintenanceMode;
use Belluga\Tenancy\Middleware\InitializeTenancyByDomain;
use Belluga\Tenancy\Tests\Etc\Tenant;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class MaintenanceModeTest extends TestCase
{
    /** @test */
    public function tenant_can_be_in_maintenance_mode()
    {
        Route::get('/foo', function () {
            return 'bar';
        })->middleware([InitializeTenancyByDomain::class, CheckTenantForMaintenanceMode::class]);

        $tenant = MaintenanceTenant::create();
        $tenant->domains()->create([
            'domain' => 'acme.localhost',
        ]);

        $this->get('http://acme.localhost/foo')
            ->assertSuccessful();

        tenancy()->end(); // flush stored tenant instance

        $tenant->putDownForMaintenance();

        $this->expectException(HttpException::class);
        $this->withoutExceptionHandling()
            ->get('http://acme.localhost/foo');
    }
}

class MaintenanceTenant extends Tenant
{
    use MaintenanceMode;
}
