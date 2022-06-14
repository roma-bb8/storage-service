<?php declare(strict_types=1);

namespace Storage\Service\Middlewares;


use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Storage\Service\Helpers\ContentTypeHelper;

class ResponseMiddleware implements MiddlewareInterface
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';

    public function call(Micro $application): bool
    {
        $application->getDI()->get('response')
            ->setContentType(ContentTypeHelper::APPLICATION_JSON, ContentTypeHelper::CHARSET)
            ->setJsonContent($application->getReturnedValue())
            ->send();

        return true;
    }
}
