<?php declare(strict_types=1);

namespace Storage\Service\Models\Security;


use Phalcon\Di\DiInterface;

interface AccessControlInterface
{
    public function isGranted(DiInterface $di, string $token): bool;
}
