<?php

declare(strict_types=1);

namespace Poplary\LumenConsul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Poplary\Consul\ServiceFactory;
use Poplary\Consul\Services\AgentInterface;
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
            $this->info('Consul Services:');
            $this->output->newLine();

            $consulUrls = explode(',', Config::get('consul.base_uris'));
            foreach ($consulUrls as $consulUrl) {
                $this->comment("Consul url: {$consulUrl}");

                $serviceFactory = new ServiceFactory(['base_uri' => $consulUrl]);
                /* @var AgentInterface $agent */
                $agent = $serviceFactory->get(AgentInterface::class);

                $response = $agent->services();
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
                        $consulService['Address'].':'.$consulService['Port'],
                    ];
                }

                $this->table(['Service', 'Service ID', 'Address'], $rows);
                $this->output->newLine();
            }
            $this->output->newLine();
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }
}
