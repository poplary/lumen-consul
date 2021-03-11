<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Poplary\Consul\ConsulResponse;
use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
use RuntimeException;
use Throwable;

/**
 * Class ConsulServiceRegister.
 */
class ConsulServiceRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consul:service:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register service to consul';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $consulUrls = explode(',', Config::get('consul.base_uris'));
            $services = Config::get('consul.services');
            foreach ($consulUrls as $consulUrl) {
                $serviceFactory = new ServiceFactory(['base_uri' => $consulUrl]);
                /* @var AgentInterface $agent */
                $agent = $serviceFactory->get(AgentInterface::class);

                foreach ($services as $consulService) {
                    /** @var ConsulResponse $response */
                    $response = $agent->registerService($consulService);
                    if (200 !== $response->getStatusCode()) {
                        throw new RuntimeException(sprintf('Service [%s] registered to consul (%s) failed.', $consulService['ID'], $consulUrl));
                    }

                    Log::info('Registered successfully.', [
                        'type' => 'consul_register',
                        'consul_url' => $consulUrl,
                        'service' => $consulService,
                    ]);
                }
            }
        } catch (Throwable $exception) {
            Log::info($exception->getMessage(), [
                'type' => 'consul_register',
                'exception' => $exception,
                'config' => Config::get('consul'),
            ]);
        }

        return 0;
    }
}
