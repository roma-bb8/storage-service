<?php declare(strict_types=1);

require __DIR__ . '/etc/bootstrap.php';

try {
    $microService = new \Storage\Service\MicroService((new \Phalcon\Di()));
    $microService->handle($microService->getDI()->get('request')->getServer('REQUEST_URI'));
} catch (Throwable $throwable) {
    (new \Phalcon\Http\Response())
        ->setContentType(
            \Storage\Service\Helpers\ContentTypeHelper::APPLICATION_JSON,
            \Storage\Service\Helpers\ContentTypeHelper::CHARSET
        )
        ->setJsonContent([
            'status' => \Storage\Service\Helpers\HttpCodeHelper::STATUS_ERROR,
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
        ])
        ->send();
}
