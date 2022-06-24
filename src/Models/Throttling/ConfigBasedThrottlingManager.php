<?php declare(strict_types=1);

namespace Storage\Service\Models\Throttling;


use Phalcon\Di\DiInterface;
use Storage\Service\Exceptions\ServiceRuntimeException;
use Storage\Service\Helpers\HttpStatusCodeHelper;

final class ConfigBasedThrottlingManager implements ThrottlingManagerInterface
{
    private const DEFAULT = 1000;

    public function isGranted(DiInterface $di, string $apiId, string $apiKey): bool
    {
        if (!in_array($apiId, $di->get('config')->get('x-api-ids')->toArray(), true)) {
            throw new ServiceRuntimeException(
                'Unidentified API Identification.',
                HttpStatusCodeHelper::BAD_REQUEST
            );
        }

        $limit = $di->get('config')->path(implode('.', [
            'throttling-limit',
            'routes',
            $di->get('request')->getServer('REQUEST_URI'),
            strtolower($di->get('request')->getMethod()),
            $apiId,
        ]), $di->get('config')->path(implode('.', [
            'throttling-limit',
            'default',
            strtolower($di->get('request')->getMethod()),
            $apiId,
        ]), self::DEFAULT));

        $isToCountKey = 'is-to-count-' . $apiId . '-' . $apiKey;
        $requestsKey = 'requests-' . $apiId . '-' . $apiKey;

        $isToCount = (bool) $di->get('memcached')->get($isToCountKey);
        $requests = $di->get('memcached')->get($requestsKey, 0);

        if ($isToCount && $limit <= $requests) {
            return false;
        }

        !$isToCount && $requests = 0;
        $di->get('memcached')->set($requestsKey, ++$requests);
        if (1 === $requests) {
            $di->get('memcached')->set($isToCountKey, 1);
        }

        return true;
    }
}
