<?php declare(strict_types=1);

namespace Storage\Service\Models\Security;


use Phalcon\Di\DiInterface;

final class ConfigBasedAccessControl implements AccessControlInterface
{
    public function isGranted(DiInterface $di, string $token): bool
    {
        return in_array(
            $token,
            $di->get('config')->get('x-api-keys')->toArray(),
            true
        );
    }
}
