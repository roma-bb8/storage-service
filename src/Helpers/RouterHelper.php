<?php declare(strict_types=1);

namespace Storage\Service\Helpers;


use Phalcon\Http\Message\Uri;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro\Collection;
use Storage\Service\Exceptions\ServiceRuntimeException;
use Storage\Service\MicroService;
use Storage\Service\Middlewares\ResponseMiddleware;
use Throwable;

class RouterHelper
{
    private const URL_SEPARATOR = '/';
    private const URL_SEPARATOR_PARAMS = '?';

    private const DEFAULT_ACTION = 'main';

    private MicroService $microService;

    private function setNotFoundHandler(): void
    {
        $this->microService->notFound(static fn () => (new Response())
            ->setContentType(
                ContentTypeHelper::APPLICATION_JSON,
                ContentTypeHelper::CHARSET
            )
            ->setJsonContent([
                'status' => HttpCodeHelper::STATUS_ERROR,
                'message' => 'Not Found...',
                'code' => 0,
            ])
            ->send()
        );
    }

    private function setErrorHandler(): void
    {
        $this->microService->error(static fn (Throwable $throwable) => (new Response())
            ->setContentType(
                ContentTypeHelper::APPLICATION_JSON,
                ContentTypeHelper::CHARSET
            )
            ->setJsonContent([
                'status' => HttpCodeHelper::STATUS_ERROR,
                'message' => $throwable->getMessage(),
                'code' => $throwable->getCode(),
            ])
            ->send()
        );
    }

    private function getLayer(): int
    {
        $layer = $this->microService->getDI()->get('request')->getHeader('x-api-layer');
        if (empty($layer)) {
            throw new ServiceRuntimeException(
                'Missing required header: X-API-Layer.',
                HttpCodeHelper::BAD_REQUEST
            );
        }

        if ($this->microService->getDI()->get('config')->path('layer.max') < $layer) {
            $layer = $this->microService->getDI()->get('config')->path('layer.max');
        }

        if ($this->microService->getDI()->get('config')->path('layer.min') > $layer) {
            $layer = $this->microService->getDI()->get('config')->path('layer.min');
        }

        return (int) $layer;
    }

    private function mount(int $layer): void
    {
        $di = $this->microService->getDI();

        $uri = $di->get(Uri::class, [$di->get('request')->getServer('REQUEST_URI')]);
        $prefix = $di->get('request')->getServer('PREFIX_ENDPOINT');

        $path = explode(self::URL_SEPARATOR_PARAMS, $uri->getPath());
        if (empty($path[0])) {
            throw new ServiceRuntimeException('Wrong url address.', HttpCodeHelper::BAD_REQUEST);
        }

        $paths = array_filter(explode(self::URL_SEPARATOR, substr($path[0], strlen($prefix))));
        if (empty($paths[0])) {
            throw new ServiceRuntimeException('Wrong url address.', HttpCodeHelper::BAD_REQUEST);
        }

        $controller = $paths[0];
        $action = strtolower($paths[1]) ?? self::DEFAULT_ACTION;

        $methodPostfix = $di->get('config')->get('controller')->get('methodPostfix');
        $classPostfix = $di->get('config')->get('controller')->get('classPostfix');
        $namespacePrefix = $di->get('config')->get('controller')->get('namespacePrefix');

        $method = strtolower($di->get('request')->getMethod());
        for ($i = $layer; $i >= $di->get('config')->path('layer.min'); $i--) {
            $className = $namespacePrefix . ucfirst($controller . $i . $classPostfix);
            $methodName = $method . ucfirst($action) . $methodPostfix;

            if (
                !class_exists($className)
                ||
                !method_exists($className, $methodName)
                ||
                !method_exists(Collection::class, $method)
            ) {
                continue;
            }

            $collection = $di->get(Collection::class)
                ->setPrefix($prefix . $controller)
                ->setHandler($className)
                ->{$method}(self::URL_SEPARATOR . $action, $method . ucfirst($action) . $methodPostfix)
                ->setLazy(true);

            $this->microService->mount($collection);

            return;
        }
    }

    public function handle(): void
    {
        $this->microService->getRouter()->removeExtraSlashes(true);

        $this->setNotFoundHandler();
        $this->setErrorHandler();

        $this->mount($this->getLayer());
    }

    public function __construct(MicroService $microService)
    {
        $this->microService = $microService;
    }
}
