<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Tests;

use Illuminate\Support\Facades\Event;
use Belluga\Tenancy\Bootstrappers\CacheTenancyBootstrapper;
use Belluga\Tenancy\Events\TenancyEnded;
use Belluga\Tenancy\Events\TenancyInitialized;
use Belluga\Tenancy\Facades\GlobalCache;
use Belluga\Tenancy\Listeners\BootstrapTenancy;
use Belluga\Tenancy\Listeners\RevertToCentralContext;
use Belluga\Tenancy\Tests\Etc\Tenant;

class GlobalCacheTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['tenancy.bootstrappers' => [
            CacheTenancyBootstrapper::class,
        ]]);

        Event::listen(TenancyInitialized::class, BootstrapTenancy::class);
        Event::listen(TenancyEnded::class, RevertToCentralContext::class);
    }

    /** @test */
    public function global_cache_manager_stores_data_in_global_cache()
    {
        $this->assertSame(null, cache('foo'));
        GlobalCache::put(['foo' => 'bar'], 1);
        $this->assertSame('bar', GlobalCache::get('foo'));

        $tenant1 = Tenant::create();
        tenancy()->initialize($tenant1);
        $this->assertSame('bar', GlobalCache::get('foo'));

        GlobalCache::put(['abc' => 'xyz'], 1);
        cache(['def' => 'ghi'], 10);
        $this->assertSame('ghi', cache('def'));

        tenancy()->end();
        $this->assertSame('xyz', GlobalCache::get('abc'));
        $this->assertSame('bar', GlobalCache::get('foo'));
        $this->assertSame(null, cache('def'));

        $tenant2 = Tenant::create();
        tenancy()->initialize($tenant2);
        $this->assertSame('xyz', GlobalCache::get('abc'));
        $this->assertSame('bar', GlobalCache::get('foo'));
        $this->assertSame(null, cache('def'));
        cache(['def' => 'xxx'], 1);
        $this->assertSame('xxx', cache('def'));

        tenancy()->initialize($tenant1);
        $this->assertSame('ghi', cache('def'));
    }
}
