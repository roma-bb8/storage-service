<?php declare(strict_types=1);

namespace Storage\Service\Commands;


use DateTime;
use JsonException;
use Storage\Service\Helpers\DateTimeHelper;

class TrashCommand extends BaseCommand
{
    private function getDateTime(int $days): DateTime
    {
        return $this->getDI()->get(DateTimeHelper::class)
            ->getUTCDateTimeByCurrent()
            ->modify("-{$days} days");
    }

    private function deleteDocuments(int $timestamp): void
    {
        $this->getDI()->get('mongodb')->storage->deleteMany(['createAt' => ['$lt' => $timestamp]]);

        $this->getTerminalManager()->green('Congratulations you cleared the old records.');
    }

    // ########################################

    public function storageAction($mode = null, $days = null): void
    {
        if ($mode === '-f') {
            if (!is_int($days) && 0 >= $days) {
                $this->getTerminalManager()->red('Days must be specified as an integer greater than 0.');

                return;
            }

            $this->deleteDocuments($this->getDateTime((int) $days)->getTimestamp());

            return;
        }

        $input = $this->getTerminalManager()->input('Number of days of relevance ?');
        $days = (int) $input->prompt();
        if (0 >= $days) {
            $this->getTerminalManager()->red('Days must be specified as an integer greater than 0.');

            return;
        }

        $timestamp = $this->getDateTime($days)->getTimestamp();
        $countDocuments = $this->getDI()->get('mongodb')->storage->countDocuments(
            ['createAt' => ['$lt' => $timestamp]]
        );

        if (0 === $countDocuments) {
            $this->getTerminalManager()->yellow('No data for this period.');

            return;
        }

        $contenderDocuments = $this->getDI()->get('mongodb')->storage->find(
            ['createAt' => ['$lt' => $timestamp]],
            ['sort' => ['timestamp' => 1], 'limit' => 5]
        );

        $this->getTerminalManager()->br()->out('Here are some entries from the selection:');
        $dateTimeHelper = $this->getDI()->get(DateTimeHelper::class);
        foreach ($contenderDocuments->toArray() as $document) {
            try {
                $createAt = $dateTimeHelper->getUTCDateTimeByTimestamp((int) $document['createAt']);

                $document['createAt'] = $createAt->format(DateTimeHelper::DATETIME_FORMAT);
                $document['_id'] = (string) $document['_id'];

                $this->getTerminalManager()->out(json_encode($document, JSON_THROW_ON_ERROR));
            } catch (JsonException $e) {
                continue;
            }
        }

        if (5 < $countDocuments) {
            $this->getTerminalManager()->out(sprintf(
                '... and before %s',
                $this->getDateTime($days)->format(DateTimeHelper::DATE_FORMAT)
            ));
        }

        $this->getTerminalManager()->out(sprintf('Records will be deleted: %d', $countDocuments));

        $input = $this->getTerminalManager()->br()->confirm('Continue ?');
        if ($input->confirmed()) {
            $this->deleteDocuments($timestamp);
        } else {
            $this->getTerminalManager()->lightGreen('You canceled the deletion. Bye!');
        }
    }
}
