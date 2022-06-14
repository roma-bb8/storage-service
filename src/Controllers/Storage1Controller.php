<?php declare(strict_types=1);

namespace Storage\Service\Controllers;


use Exception;
use MongoDB\Collection;
use Storage\Service\Helpers\DateTimeHelper;
use Storage\Service\Helpers\HttpCodeHelper;
use Storage\Service\Models\Document;

class Storage1Controller extends BaseController
{
    public function getEntityAction(): array
    {
        try {
            /** @var Collection $collection */
            $collection = $this->getDI()->get('mongodb')->storage;

            $filter = [
                'key1' => htmlspecialchars($this->getDI()->get('request')->get('key1', null, '')),
                'key2' => htmlspecialchars($this->getDI()->get('request')->get('key2', null, '')),
            ];

            return [
                'status' => HttpCodeHelper::STATUS_SUCCESS,
                'data' => $collection->find($filter)->toArray(),
            ];
        } catch (Exception $e) {
            return [
                'status' => HttpCodeHelper::STATUS_ERROR,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function postEntityAction(): array
    {
        $currentDateTime = $this->getDI()->get(DateTimeHelper::class)->getUTCDateTimeByCurrent();
        $data = $this->getDI()->get('runtime-cache')->get('request-data');

        $documents = [];
        $errors = [];
        foreach ($data as $key => $item) {
            $item['createAt'] = $currentDateTime->getTimestamp();
            $document = $this->getDI()->get(Document::class, [$item]);

            $messages = $document->validate($this->getDI());
            if (empty($messages->count())) {
                $documents[] = $document;
            } else {
                $errors[$key] = $messages;
            }
        }

        if (!empty($errors)) {
            return [
                'status' => HttpCodeHelper::STATUS_ERROR,
                'messages' => $errors,
            ];
        }

        try {
            /** @var Collection $collection */
            $collection = $this->getDI()->get('mongodb')->storage;
            $result = $collection->insertMany($documents);

            return [
                'status' => HttpCodeHelper::STATUS_SUCCESS,
                'data' => $collection->find(['_id' => ['$in' => $result->getInsertedIds()]])->toArray(),
            ];
        } catch (Exception $e) {
            return [
                'status' => HttpCodeHelper::STATUS_ERROR,
                'messages' => $e->getMessage(),
            ];
        }
    }
}
