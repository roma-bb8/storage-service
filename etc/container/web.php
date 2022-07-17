<?php declare(strict_types=1);

return [
    'eventsManager' => [
        'className' => \Phalcon\Events\Manager::class,
        'shared' => true,
    ],
    'url' => [
        'className' => \Phalcon\Url::class,
        'shared' => true,
    ],
    'router' => [
        'className' => \Phalcon\Mvc\Router::class,
        'shared' => true,
    ],
    'request' => [
        'className' => \Phalcon\Http\Request::class,
        'shared' => true,
    ],
    'response' => [
        'className' => \Phalcon\Http\Response::class,
        'shared' => true,
    ],
    'runtime-cache' => [
        'className' => \Phalcon\Cache\Adapter\Memory::class,
        'shared' => true,
        'arguments' => [
            [
                'type' => 'service',
                'name' => \Phalcon\Storage\SerializerFactory::class,
            ],
            [
                'type' => 'parameter',
                'value' => [
                    'defaultSerializer' => 'Php',
                    'lifetime' => \Storage\Service\MicroService::getConfig('runtime-cache.lifetime'),
                ],
            ],
        ],
    ],
    'memcached' => [
        'className' => \Phalcon\Cache\Adapter\Libmemcached::class,
        'shared' => true,
        'arguments' => [
            [
                'type' => 'service',
                'name' => \Phalcon\Storage\SerializerFactory::class,
            ],
            [
                'type' => 'parameter',
                'value' => [
                    'lifetime' => \Storage\Service\MicroService::getConfig('memcached.lifetime'),
                    'servers' => \Storage\Service\MicroService::getConfig('memcached.servers'),
                ],
            ],
        ],
    ],
    \MongoDB\Client::class => [
        'className' => \MongoDB\Client::class,
        'shared' => true,
        'arguments' => [
            [
                'type' => 'parameter',
                'value' => sprintf('mongodb://%s:%s@%s:%d/%s',
                    \Storage\Service\MicroService::getConfig('mongodb.user'),
                    \Storage\Service\MicroService::getConfig('mongodb.password'),
                    \Storage\Service\MicroService::getConfig('mongodb.host'),
                    \Storage\Service\MicroService::getConfig('mongodb.port'),
                    \Storage\Service\MicroService::getConfig('mongodb.dbname'),
                ),
            ],
        ],
    ],
    \Phalcon\Http\Message\Uri::class => [
        'className' => \Phalcon\Http\Message\Uri::class,
    ],
    \Phalcon\Mvc\Micro\Collection::class => [
        'className' => \Phalcon\Mvc\Micro\Collection::class,
        'shared' => true,
    ],
    \Phalcon\Storage\SerializerFactory::class => [
        'className' => \Phalcon\Storage\SerializerFactory::class,
        'shared' => true,
    ],
    \Phalcon\Validation::class => [
        'className' => \Phalcon\Validation::class,
    ],
    \Phalcon\Validation\Validator\PresenceOf::class => [
        'className' => \Phalcon\Validation\Validator\PresenceOf::class,
    ],
    \Phalcon\Validation\Validator\Callback::class => [
        'className' => \Phalcon\Validation\Validator\Callback::class,
    ],
    \Phalcon\Validation\Validator\Regex::class => [
        'className' => \Phalcon\Validation\Validator\Regex::class,
    ],
    \Phalcon\Validation\Validator\Email::class => [
        'className' => \Phalcon\Validation\Validator\Email::class,
    ],
    \Storage\Service\Helpers\RouterHelper::class => [
        'className' => \Storage\Service\Helpers\RouterHelper::class,
        'shared' => true,
    ],
    \Storage\Service\Helpers\DateTimeHelper::class => [
        'className' => \Storage\Service\Helpers\DateTimeHelper::class,
        'shared' => true,
    ],
    \Storage\Service\Middlewares\ResponseMiddleware::class => [
        'className' => \Storage\Service\Middlewares\ResponseMiddleware::class,
        'shared' => true,
    ],
    \Storage\Service\Middlewares\RequestMiddleware::class => [
        'className' => \Storage\Service\Middlewares\RequestMiddleware::class,
        'shared' => true,
    ],
    \Storage\Service\Models\Throttling\ThrottlingManagerInterface::class => [
        'className' => \Storage\Service\Models\Throttling\ConfigBasedThrottlingManager::class,
        'shared' => true,
    ],
    \Storage\Service\Models\Security\AccessControlInterface::class => [
        'className' => \Storage\Service\Models\Security\ConfigBasedAccessControl::class,
        'shared' => true,
    ],
    \Storage\Service\Factorys\Validators\DocumentValidatorFactory::class => [
        'className' => \Storage\Service\Factorys\Validators\DocumentValidatorFactory::class,
        'shared' => true,
    ],
    \Storage\Service\Models\Document::class => [
        'className' => \Storage\Service\Models\Document::class,
    ],
];
