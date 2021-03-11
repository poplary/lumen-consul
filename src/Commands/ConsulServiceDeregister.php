<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Poplary\Consul\ConsulResponse;
use Poplary\LumenConsul\Facades\ConsulAgent;

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
                $this->comment('Consul HTTP 地址:');
                $this->line(sprintf(' - <info>%s</info>', $consulUrl));

                $serviceId = $this->argument('service_id');

                $this->comment('取消注册服务:');
                $this->line(sprintf(' - ID: <info>%s</info>', $serviceId));

                /** @var ConsulResponse $response */
                $response = ConsulAgent::deregisterService($serviceId);

                $this->line(sprintf(' - 取消注册结果: <info>%s</info>', json_encode($response->getBody())));
                $this->output->newLine();
            }
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }
}
