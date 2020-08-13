<?php

namespace Poplary\LumenConsul;

use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
use Poplary\Consul\Services\CatalogInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

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
    protected function registerConsulServiceFactory()
    {
        $this->app->singleton('consul.service.factory', function () {
            return new ServiceFactory([
                'base_uri' => config('consul.base_uri'),
            ]);
        });
    }

    /**
     * CatalogService 单例.
     */
    protected function registerConsulCatalogService()
    {
        $this->app->singleton('consul.service.catalog', function () {
            return app('consul.service.factory')->get(CatalogInterface::class);
        });
    }

    /**
     * AgentService 单例.
     */
    protected function registerConsulAgentService()
    {
        $this->app->singleton('consul.service.agent', function () {
            return app('consul.service.factory')->get(AgentInterface::class);
        });
    }
}
