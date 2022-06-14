<?php declare(strict_types=1);

namespace Storage\Service\Commands;

class HelpCommand extends BaseCommand
{
    public function mainAction(): void
    {
        $this->getTerminalManager()->br()->out('Usage:');
        $this->getTerminalManager()->out('php ./bin/cli command [ method ] [ args ]');

        $this->getTerminalManager()->br()->out('Commands:');
        $this->getTerminalManager()->out('help [ main ] (Will display this message)');
        $this->getTerminalManager()->out('trash storage [--force|-f] [days] (Remove entries from the collection)');
    }
}
