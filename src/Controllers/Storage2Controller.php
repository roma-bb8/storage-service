<?php declare(strict_types=1);

namespace Storage\Service\Controllers;

class Storage2Controller extends Storage1Controller
{
    public function getEntityAction(): array
    {
        $documents = parent::getEntityAction();

        foreach ($documents['data'] as &$document) {
            $document['_id'] = (string) $document['_id'];
        }
        unset($document);

        return $documents;
    }
}
