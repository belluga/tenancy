<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Features;

use Illuminate\Foundation\Application;
use Belluga\Tenancy\Contracts\Feature;
use Belluga\Tenancy\Tenancy;
use Belluga\Tenancy\Vite;

class ViteBundler implements Feature
{
    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap(Tenancy $tenancy): void
    {
        $this->app->singleton(\Illuminate\Foundation\Vite::class, Vite::class);
    }
}
