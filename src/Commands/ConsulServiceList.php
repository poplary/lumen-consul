<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Poplary\Consul\ConsulResponse;
use Poplary\LumenConsul\Facades\ConsulCatalog;
use RuntimeException;

/**
 * Class ConsulServiceList.
 */
class ConsulServiceList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consul:service:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List service of consul';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->comment('Consul HTTP 地址:');
            $this->line(sprintf(' - <info>%s</info>', Config::get('consul.base_uri')));
            $this->output->newLine();

            $this->comment('Services:');

            /** @var ConsulResponse $response */
            $response = ConsulCatalog::services();
            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException('出错了！响应的 status 不为 200.');
            }

            $services = json_decode($response->getBody(), true);

            if (empty($services)) {
                $this->error(' - 查询不到注册的服务.');
            }

            foreach ($services as $consulService) {
                $this->line(sprintf('  - 服务名称: <info>%s</info>', $consulService['Service']));
                $this->line(sprintf('  - ID: <info>%s</info>', $consulService['ID']));
                $this->line(sprintf('  - 地址: <info>%s</info>', $consulService['Address']));
                $this->line(sprintf('  - 端口: <info>%s</info>', $consulService['Port']));
                $this->output->newLine();
            }
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }
}
