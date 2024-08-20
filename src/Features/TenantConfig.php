<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Features;

use Illuminate\Contracts\Config\Repository;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Belluga\Tenancy\Contracts\Feature;
use Belluga\Tenancy\Contracts\Tenant;
use Belluga\Tenancy\Events\RevertedToCentralContext;
use Belluga\Tenancy\Events\TenancyBootstrapped;
use Belluga\Tenancy\Tenancy;

class TenantConfig implements Feature
{
    /** @var Repository */
    protected $config;

    /** @var array */
    public $originalConfig = [];

    public static $storageToConfigMap = [
        // 'paypal_api_key' => 'services.paypal.api_key',
    ];

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function bootstrap(Tenancy $tenancy): void
    {
        Event::listen(TenancyBootstrapped::class, function (TenancyBootstrapped $event) {
            $this->setTenantConfig($event->tenancy->tenant);
        });

        Event::listen(RevertedToCentralContext::class, function () {
            $this->unsetTenantConfig();
        });
    }

    public function setTenantConfig(Tenant $tenant): void
    {
        /** @var Tenant|Model $tenant */
        foreach (static::$storageToConfigMap as $storageKey => $configKey) {
            $override = Arr::get($tenant, $storageKey);

            if (! is_null($override)) {
                if (is_array($configKey)) {
                    foreach ($configKey as $key) {
                        $this->originalConfig[$key] = $this->originalConfig[$key] ?? $this->config[$key];

                        $this->config[$key] = $override;
                    }
                } else {
                    $this->originalConfig[$configKey] = $this->originalConfig[$configKey] ?? $this->config[$configKey];

                    $this->config[$configKey] = $override;
                }
            }
        }
    }

    public function unsetTenantConfig(): void
    {
        foreach ($this->originalConfig as $key => $value) {
            $this->config[$key] = $value;
        }
    }
}
