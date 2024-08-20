<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Tests;

use Illuminate\Support\Facades\Route;
use Belluga\Tenancy\Database\Concerns\HasDomains;
use Belluga\Tenancy\Database\Models;
use Belluga\Tenancy\Exceptions\NotASubdomainException;
use Belluga\Tenancy\Middleware\InitializeTenancyBySubdomain;

class SubdomainTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Global state cleanup after some tests
        InitializeTenancyBySubdomain::$onFail = null;

        Route::group([
            'middleware' => InitializeTenancyBySubdomain::class,
        ], function () {
            Route::get('/foo/{a}/{b}', function ($a, $b) {
                return "$a + $b";
            });
        });

        config(['tenancy.tenant_model' => SubdomainTenant::class]);
    }

    /** @test */
    public function tenant_can_be_identified_by_subdomain()
    {
        $tenant = SubdomainTenant::create([
            'id' => 'acme',
        ]);

        $tenant->domains()->create([
            'domain' => 'foo',
        ]);

        $this->assertFalse(tenancy()->initialized);

        $this
            ->get('http://foo.localhost/foo/abc/xyz')
            ->assertSee('abc + xyz');

        $this->assertTrue(tenancy()->initialized);
        $this->assertSame('acme', tenant('id'));
    }

    /** @test */
    public function onfail_logic_can_be_customized()
    {
        InitializeTenancyBySubdomain::$onFail = function () {
            return 'foo';
        };

        $this
            ->get('http://foo.localhost/foo/abc/xyz')
            ->assertSee('foo');
    }

    /** @test */
    public function localhost_is_not_a_valid_subdomain()
    {
        $this->expectException(NotASubdomainException::class);

        $this
            ->withoutExceptionHandling()
            ->get('http://localhost/foo/abc/xyz');
    }

    /** @test */
    public function ip_address_is_not_a_valid_subdomain()
    {
        $this->expectException(NotASubdomainException::class);

        $this
            ->withoutExceptionHandling()
            ->get('http://127.0.0.1/foo/abc/xyz');
    }

    /** @test */
    public function oninvalidsubdomain_logic_can_be_customized()
    {
        // in this case, we need to return a response instance
        // since a string would be treated as the subdomain
        InitializeTenancyBySubdomain::$onFail = function ($e) {
            if ($e instanceof NotASubdomainException) {
                return response('foo custom invalid subdomain handler');
            }

            throw $e;
        };

        $this
            ->withoutExceptionHandling()
            ->get('http://127.0.0.1/foo/abc/xyz')
            ->assertSee('foo custom invalid subdomain handler');
    }

    /** @test */
    public function we_cant_use_a_subdomain_that_doesnt_belong_to_our_central_domains()
    {
        config(['tenancy.central_domains' => [
            '127.0.0.1',
            // not 'localhost'
        ]]);

        $tenant = SubdomainTenant::create([
            'id' => 'acme',
        ]);

        $tenant->domains()->create([
            'domain' => 'foo',
        ]);

        $this->expectException(NotASubdomainException::class);

        $this
            ->withoutExceptionHandling()
            ->get('http://foo.localhost/foo/abc/xyz');
    }

    /** @test */
    public function central_domain_is_not_a_subdomain()
    {
        config(['tenancy.central_domains' => [
            'localhost',
        ]]);

        $tenant = SubdomainTenant::create([
            'id' => 'acme',
        ]);

        $tenant->domains()->create([
            'domain' => 'acme',
        ]);

        $this->expectException(NotASubdomainException::class);

        $this
            ->withoutExceptionHandling()
            ->get('http://localhost/foo/abc/xyz');
    }
}

class SubdomainTenant extends Models\Tenant
{
    use HasDomains;
}
