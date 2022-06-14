<?php declare(strict_types=1);

namespace Storage\Service\Commands;


use League\CLImate\CLImate;
use Phalcon\Cli\Task;

class BaseCommand extends Task
{
    public function getTerminalManager(): CLImate
    {
        return $this->getDI()->get(CLImate::class);
    }
}
