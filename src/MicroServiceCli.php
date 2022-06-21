<?php declare(strict_types=1);

namespace Storage\Service;


use Phalcon\Cli\Console;
use Phalcon\Di\DiInterface;

final class MicroServiceCli extends Console
{
    use DiTrait;

    public function handle(array $arguments = null): void
    {
        $args = [];
        foreach ($arguments as $k => $arg) {
            if ($k === 1) {
                $args['task'] = $arg;
            } elseif ($k === 2) {
                $args['action'] = $arg;
            } elseif ($k >= 3) {
                $args['params'][] = $arg;
            }
        }

        parent::handle($args);
    }

    public function __construct(DiInterface $container)
    {
        parent::__construct($container);

        $this->setContainer('cli');

        $dispatcher = $this->getDI()->get('dispatcher');
        $dispatcher->setDefaultNamespace($this->getDI()->get('config')->path('command.namespacePrefix'));
        $dispatcher->setTaskSuffix($this->getDI()->get('config')->path('command.classPostfix'));
        $dispatcher->setDefaultTask('help');
    }
}
