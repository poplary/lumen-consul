<?php

namespace Poplary\LumenConsul\Facades;

use Poplary\Consul\Services\CatalogInterface;
use Illuminate\Support\Facades\Facade;

/**
 * Class ConsulAgent.
 *
 * @see CatalogInterface
 *
 * @method static register($node)
 * @method static deregister($node)
 * @method static datacenters()
 * @method static nodes(array $options = [])
 * @method static node($node, array $options = [])
 * @method static services(array $options = [])
 * @method static service($service, array $options = [])
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
