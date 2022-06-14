<?php declare(strict_types=1);

namespace Storage\Service;


use Phalcon\Di\DiInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Mvc\Micro;
use Storage\Service\Helpers\RouterHelper;
use Storage\Service\Middlewares\RequestMiddleware;
use Storage\Service\Middlewares\ResponseMiddleware;

final class MicroService extends Micro
{
    use DiTrait;

    public function setEventsManager(ManagerInterface $eventsManager): void
    {
        $eventsManager->attach('micro', $this->getDI()->get(RequestMiddleware::class));

        $this->before($this->getDI()->get(RequestMiddleware::class));
        $this->after($this->getDI()->get(ResponseMiddleware::class));

        parent::setEventsManager($eventsManager);
    }

    public function __construct(DiInterface $container)
    {
        parent::__construct($container);

        $this->setContainer('web');
        $this->setEventsManager($this->getDI()->get('eventsManager'));

        $this->getDI()->get(RouterHelper::class, [$this])->handle();
    }
}
