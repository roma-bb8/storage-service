<?php declare(strict_types=1);

return [
    'layer' => [
        'min' => 1,
        'max' => 2,
    ],
    'controller' => [
        'namespacePrefix' => '\\Storage\\Service\\Controllers\\',
        'classPostfix' => 'Controller',
        'methodPostfix' => 'Action',
    ],
    'command' => [
        'namespacePrefix' => '\\Storage\\Service\\Commands\\',
        'classPostfix' => 'Command',
    ],
    'x-api-ids' => [
        'US-1',
        'UK-1',
    ],
    'x-api-keys' => [
        'fc3ff98e8c6a0d3087d515c0473f8677',
    ],
    'runtime-cache' => [
        'lifetime' => 3600,
    ],
    'memcached' => [
        'lifetime' => 3600,
        'servers' => [
            0 => [
                'host' => 'memcached',
                'port' => 11211,
                'weight' => 1,
            ],
        ],
    ],
    'throttling-limit' => [
        // всі значення вказані скільки можна робити запитів за годину
        'default' => [
            'post' => [
                'US-1' => 100,
                'UK-1' => 50,
            ],
            'get' => [
                'US-1' => 1000,
                'UK-1' => 500,
            ],
        ],
        'routes' => [
            // вказувати повний URI з змінної REQUEST_URI
            // приклад:
            // '/api/v1/storage/entity/' => [
            //    'post' => [
            //        'US-1' => 100,
            //        'UK-1' => 50,
            //    ],
            //    'get' => [
            //        'US-1' => 1000,
            //        'UK-1' => 500,
            //    ],
            // ],
        ],
    ],
    'mongodb' => [
        'user' => getenv('MONGO_USERNAME', true),
        'password' => getenv('MONGO_PASSWORD', true),
        'host' => 'mongodb',
        'port' => 27017,
        'dbname' => getenv('MONGO_DATABASE', true),
    ],
];
