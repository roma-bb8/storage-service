<?php declare(strict_types=1);

namespace Storage\Service\Controllers;


use MongoDB\Collection;
use Storage\Service\Helpers\DateTimeHelper;
use Storage\Service\Middlewares\ResponseMiddleware;
use Storage\Service\Models\Document;

class Storage1Controller extends BaseController
{
    public function getEntityAction(): array
    {
        $filterCondition = [
            'key1' => $this->getDI()->get('request')->get('key1'),
            'key2' => $this->getDI()->get('request')->get('key2'),
        ];

        $messages = $this->getDI()->get(Document::class, [$filterCondition])->validate($this->getDI());
        if (0 !== $messages->count()) {
            return [
                'status' => ResponseMiddleware::STATUS_ERROR,
                'messages' => $messages,
            ];
        }

        /** @var Collection $collection */
        $collection = $this->getDI()->get('mongodb')->storage;

        return [
            'status' => ResponseMiddleware::STATUS_SUCCESS,
            'data' => $collection->find($filterCondition)->toArray(),
        ];
    }

    public function postEntityAction(): array
    {
        $requestData = $this->getDI()->get('runtime-cache')->get('request-data');

        $currentDateTime = $this->getDI()->get(DateTimeHelper::class)->getUTCDateTimeByCurrent();
        $documents = [];
        $errors = [];
        foreach ($requestData as $key => $data) {
            $data['createAt'] = $currentDateTime->getTimestamp();

            $document = $this->getDI()->get(Document::class, [$data]);
            $messages = $document->validate($this->getDI());
            if (0 === $messages->count()) {
                $documents[] = $document;

                continue;
            }

            $errors[$key] = $messages;
        }

        if (!empty($errors)) {
            return [
                'status' => ResponseMiddleware::STATUS_ERROR,
                'messages' => $errors,
            ];
        }

        /** @var Collection $collection */
        $collection = $this->getDI()->get('mongodb')->storage;

        $result = $collection->insertMany($documents);

        return [
            'status' => ResponseMiddleware::STATUS_SUCCESS,
            'data' => $collection->find(['_id' => ['$in' => $result->getInsertedIds()]])->toArray(),
        ];
    }
}
