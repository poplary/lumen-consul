<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Facades;

use Poplary\Consul\ConsulResponse;
use Poplary\Consul\Services\AgentInterface;
use Illuminate\Support\Facades\Facade;

/**
 * Class ConsulAgent.
 *
 * @mixin AgentInterface
 *
 * @method static ConsulResponse checks()
 * @method static ConsulResponse services()
 * @method static ConsulResponse members(array $options = array())
 * @method static ConsulResponse self()
 * @method static ConsulResponse join($address, array $options = array())
 * @method static ConsulResponse forceLeave($node)
 * @method static ConsulResponse registerCheck($check)
 * @method static ConsulResponse deregisterCheck($checkId)
 * @method static ConsulResponse passCheck($checkId, array $options = array())
 * @method static ConsulResponse warnCheck($checkId, array $options = array())
 * @method static ConsulResponse failCheck($checkId, array $options = array())
 * @method static ConsulResponse registerService($service)
 * @method static ConsulResponse deregisterService($serviceId)
 */
class ConsulAgent extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'consul.service.agent';
    }
}
