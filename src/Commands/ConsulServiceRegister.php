<?php

namespace Poplary\LumenConsul\Commands;

use Poplary\Consul\ConsulResponse;
use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
use Illuminate\Console\Command;

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
            $consulUrls = explode(',', config('consul.base_uris'));
            $services = config('consul.services');

            foreach ($consulUrls as $consulUrl) {
                $this->comment('Consul HTTP 地址:');
                $this->line(sprintf(' - <info>%s</info>', $consulUrl));
                $serviceFactory = new ServiceFactory(['base_uri' => $consulUrl]);
                /* @var AgentInterface $agent */
                $agent = $serviceFactory->get(AgentInterface::class);

                foreach ($services as $consulService) {
                    $this->comment('注册服务:');
                    $this->line(sprintf(' - 服务名称: <info>%s</info>', $consulService['Name']));
                    $this->line(sprintf(' - ID: <info>%s</info>', $consulService['ID']));
                    $this->line(sprintf(' - 地址: <info>%s</info>', $consulService['Address']));
                    $this->line(sprintf(' - 端口: <info>%s</info>', $consulService['Port']));
                    /* @var ConsulResponse $response */
                    $response = $agent->registerService($consulService);
                    if (200 !== $response->getStatusCode()) {
                        throw new \Exception('注册失败');
                    }
                    $this->info(' √ 注册成功.');
                    $this->output->newLine();
                }
                $this->output->newLine();
            }
        } catch (\Throwable $exception) {
            $this->error(' x 注册失败.'.$exception->getMessage());
        }

        return 0;
    }
}
