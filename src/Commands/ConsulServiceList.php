<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Poplary\LumenConsul\Facades\ConsulCatalog;
use RuntimeException;
use Throwable;

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

            $response = ConsulCatalog::services();
            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException(sprintf('[%s] Response error: %s', $response->getStatusCode(), $response->getBody()));
            }

            $services = json_decode($response->getBody(), true);
            if (empty($services)) {
                throw new RuntimeException('No service found.');
            }

            $rows = [];
            foreach ($services as $consulService) {
                $rows[] = [
                    $consulService['Service'],
                    $consulService['ID'],
                    $consulService['Address'],
                    $consulService['Port'],
                ];
            }

            $this->table(['Service', 'Service ID', 'Address', 'Port'], $rows);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }
}
