<?php

namespace Poplary\LumenConsul\Facades;

use Poplary\Consul\Services\AgentInterface;
use Illuminate\Support\Facades\Facade;

/**
 * Class ConsulAgent.
 *
 * @see AgentInterface
 *
 * @method static checks()
 * @method static services()
 * @method static members(array $options = array())
 * @method static self()
 * @method static join($address, array $options = array())
 * @method static forceLeave($node)
 * @method static registerCheck($check)
 * @method static deregisterCheck($checkId)
 * @method static passCheck($checkId, array $options = array())
 * @method static warnCheck($checkId, array $options = array())
 * @method static failCheck($checkId, array $options = array())
 * @method static registerService($service)
 * @method static deregisterService($serviceId)
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
