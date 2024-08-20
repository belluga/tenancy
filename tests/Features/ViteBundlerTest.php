<?php

declare(strict_types=1);

use Belluga\Tenancy\Features\ViteBundler;
use Belluga\Tenancy\Tests\Etc\Tenant;
use Belluga\Tenancy\Tests\TestCase;
use Belluga\Tenancy\Vite as StanclVite;

class ViteBundlerTest extends TestCase
{
    /** @test */
    public function the_vite_helper_uses_our_custom_class()
    {
        $vite = app(\Illuminate\Foundation\Vite::class);

        $this->assertInstanceOf(\Illuminate\Foundation\Vite::class, $vite);
        $this->assertNotInstanceOf(StanclVite::class, $vite);

        config([
            'tenancy.features' => [ViteBundler::class],
        ]);

        $tenant = Tenant::create();

        tenancy()->initialize($tenant);

        app()->forgetInstance(\Illuminate\Foundation\Vite::class);

        $vite = app(\Illuminate\Foundation\Vite::class);

        $this->assertInstanceOf(StanclVite::class, $vite);
    }
}
