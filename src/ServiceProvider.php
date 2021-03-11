<?php

declare(strict_types=1);

namespace Poplary\LumenConsul;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
use Poplary\Consul\Services\CatalogInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadCommands();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerConsulServiceFactory();
        $this->registerConsulCatalogService();
        $this->registerConsulAgentService();
    }

    /**
     * 加载命令.
     */
    protected function loadCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ConsulServiceList::class,
                Commands\ConsulServiceRegister::class,
                Commands\ConsulServiceDeregister::class,
            ]);
        }
    }

    /**
     * ServiceFactory 单例.
     */
    protected function registerConsulServiceFactory(): void
    {
        $this->app->singleton('consul.service.factory', function () {
            return new ServiceFactory([
                'base_uri' => Config::get('consul.base_uri'),
            ]);
        });
    }

    /**
     * CatalogService 单例.
     */
    protected function registerConsulCatalogService(): void
    {
        $this->app->singleton('consul.service.catalog', function (Application $app) {
            return $app->get('consul.service.factory')->get(CatalogInterface::class);
        });
    }

    /**
     * AgentService 单例.
     */
    protected function registerConsulAgentService(): void
    {
        $this->app->singleton('consul.service.agent', function (Application $app) {
            return $app->get('consul.service.factory')->get(AgentInterface::class);
        });
    }
}
