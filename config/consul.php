<?php

return [
    /*
     * Consul HTTP 服务的地址
     */
    'base_uri' => env('CONSUL_HTTP_ADDR', 'http://127.0.0.1:8500'),

    /*
     * 需要注册到 Consul 的服务
     * 字段需按照 Consul 接口的要求设置
     */
    'services' => [
        [
            'Name' => env('CONSUL_SERVICE_NAME'),
            'ID' => env('CONSUL_SERVICE_ID'),
            'Tags' => [
                env('CONSUL_SERVICE_TAG'),
            ],
            'Address' => env('CONSUL_SERVICE_ADDRESS'),
            'Port' => (int) env('CONSUL_SERVICE_PORT'),
            'EnableTagOverride' => false,
            'Check' => [
                'DeregisterCriticalServiceAfter' => '90m',
                'Tcp' => env('CONSUL_SERVICE_ADDRESS').':'.env('CONSUL_SERVICE_PORT'),
                'Interval' => '10s',
                'Timeout' => '5s',
            ],
        ],
    ],
];
