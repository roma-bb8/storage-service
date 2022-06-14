<?php declare(strict_types=1);

namespace Storage\Service\Middlewares;


use JsonException;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Storage\Service\Exceptions\ServiceRuntimeException;
use Storage\Service\Helpers\HttpCodeHelper;
use Storage\Service\Models\Security\AccessControlInterface;
use Storage\Service\Models\Throttling\ThrottlingManagerInterface;

class RequestMiddleware implements MiddlewareInterface
{
    public function beforeHandleRoute(Event $event, Micro $application): bool
    {
        $di = $application->getDI();

        $apiId = $di->get('request')->getHeader('x-api-id');
        if (empty($apiId)) {
            throw new ServiceRuntimeException(
                'Missing required header: X-Api-Id.',
                HttpCodeHelper::BAD_REQUEST
            );
        }

        $apiKey = $di->get('request')->getHeader('x-api-key');
        if (empty($apiKey)) {
            throw new ServiceRuntimeException(
                'Missing required header: X-Api-Key.',
                HttpCodeHelper::BAD_REQUEST
            );
        }

        if (!$di->get(ThrottlingManagerInterface::class)->isGranted($di, $apiId, $apiKey)) {
            throw new ServiceRuntimeException(
                'Service access limit exceeded.',
                HttpCodeHelper::FORBIDDEN
            );
        }

        if (!$di->get(AccessControlInterface::class)->isGranted($di, $apiKey)) {
            throw new ServiceRuntimeException(
                'Access denied.',
                HttpCodeHelper::FORBIDDEN
            );
        }

        return true;
    }

    public function beforeExecuteRoute(Event $event, Micro $application): bool
    {
        if (!$application->getDI()->get('request')->isMethod(['POST'], true)) {
            return true;
        }

        try {

            $data = json_decode(
                $application->getDI()->get('request')->getRawBody(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $application->getDI()->get('runtime-cache')->set('request-data', $data);

        } catch (JsonException $exception) {
            throw new ServiceRuntimeException('JSON decode error.', HttpCodeHelper::BAD_REQUEST);
        }

        return true;
    }

    public function call(Micro $application): bool
    {
        return true;
    }
}
