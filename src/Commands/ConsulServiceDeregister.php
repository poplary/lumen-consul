<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Poplary\Consul\ConsulResponse;
use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
use Throwable;

/**
 * Class ConsulServiceDeregister.
 */
class ConsulServiceDeregister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consul:service:deregister {service_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deregister service to consul';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $consulUrls = explode(',', Config::get('consul.base_uris'));

            foreach ($consulUrls as $consulUrl) {
                $serviceFactory = new ServiceFactory(['base_uri' => $consulUrl]);
                /* @var AgentInterface $agent */
                $agent = $serviceFactory->get(AgentInterface::class);

                $serviceId = $this->argument('service_id');

                /** @var ConsulResponse $response */
                $response = $agent->deregisterService($serviceId);

                Log::info('Deregister result: '.json_encode($response->getBody()), [
                    'type' => 'consul_deregister',
                    'consul_url' => $consulUrl,
                    'service_id' => $serviceId,
                ]);
            }
        } catch (Throwable $exception) {
            Log::info($exception->getMessage(), [
                'type' => 'consul_deregister',
                'exception' => $exception,
                'config' => Config::get('consul'),
            ]);
        }

        return 0;
    }
}
