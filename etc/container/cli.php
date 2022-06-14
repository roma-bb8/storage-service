<?php declare(strict_types=1);

return [
    'dispatcher' => [
        'className' => \Phalcon\Cli\Dispatcher::class,
        'shared' => true,
    ],
    'router' => [
        'className' => \Phalcon\Cli\Router::class,
        'shared' => true,
    ],
    \MongoDB\Client::class => [
        'className' => \MongoDB\Client::class,
        'shared' => true,
        'arguments' => [
            [
                'type' => 'parameter',
                'value' => sprintf('mongodb://%s:%s@%s:%d',
                    \Storage\Service\MicroService::getConfig('mongodb.user'),
                    \Storage\Service\MicroService::getConfig('mongodb.password'),
                    \Storage\Service\MicroService::getConfig('mongodb.host'),
                    \Storage\Service\MicroService::getConfig('mongodb.port'),
                ),
            ],
        ],
    ],
    \League\CLImate\CLImate::class => [
        'className' => \League\CLImate\CLImate::class,
        'shared' => true,
    ],
    \Storage\Service\Helpers\DateTimeHelper::class => [
        'className' => \Storage\Service\Helpers\DateTimeHelper::class,
        'shared' => true,
    ],
];
