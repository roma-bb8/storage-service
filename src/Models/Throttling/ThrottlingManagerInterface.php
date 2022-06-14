<?php declare(strict_types=1);

namespace Storage\Service\Models\Throttling;


use Phalcon\Di\DiInterface;

interface ThrottlingManagerInterface
{
    public function isGranted(DiInterface $di, string $apiId, string $apiKey): bool;
}
