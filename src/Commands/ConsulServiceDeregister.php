<?php

namespace Poplary\LumenConsul\Commands;

use Poplary\Consul\ConsulResponse;
use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
use Illuminate\Console\Command;

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
            $consulUrls = explode(',', config('consul.base_uris'));
            foreach ($consulUrls as $consulUrl) {
                $this->comment('Consul HTTP 地址:');
                $this->line(sprintf(' - <info>%s</info>', $consulUrl));
                $serviceFactory = new ServiceFactory(['base_uri' => $consulUrl]);
                /* @var AgentInterface $agent */
                $agent = $serviceFactory->get(AgentInterface::class);

                $serviceId = $this->argument('service_id');

                $this->comment('取消注册服务:');
                $this->line(sprintf(' - ID: <info>%s</info>', $serviceId));

                /** @var ConsulResponse $response */
                $response = $agent->deregisterService($serviceId);

                $this->line(sprintf(' - 取消注册结果: <info>%s</info>', json_encode($response->getBody())));
                $this->output->newLine();
            }
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }
}
