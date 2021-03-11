<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Facades;

use Poplary\Consul\ConsulResponse;
use Poplary\Consul\Services\CatalogInterface;
use Illuminate\Support\Facades\Facade;

/**
 * Class ConsulAgent.
 *
 * @mixin CatalogInterface
 *
 * @method static ConsulResponse register($node)
 * @method static ConsulResponse deregister($node)
 * @method static ConsulResponse datacenters()
 * @method static ConsulResponse nodes(array $options = [])
 * @method static ConsulResponse node($node, array $options = [])
 * @method static ConsulResponse services(array $options = [])
 * @method static ConsulResponse service($service, array $options = [])
 */
class ConsulCatalog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'consul.service.catalog';
    }
}
