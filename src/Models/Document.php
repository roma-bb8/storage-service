<?php declare(strict_types=1);

namespace Storage\Service\Models;


use DateTime;
use DateTimeZone;
use MongoDB\BSON\Unserializable;
use MongoDB\BSON\Serializable;
use Phalcon\Di\DiInterface;
use Phalcon\Messages\Messages;
use Phalcon\Validation;
use Storage\Service\Factorys\Validators\DocumentValidator;
use Storage\Service\Helpers\DateTimeHelper;

class Document implements Unserializable, Serializable
{
    private string $key1;
    private string $key2;
    private array $value;
    private DateTime $createAt;

    public function validate(DiInterface $di): Messages
    {
        $validation = $di->get(Validation::class);

        $validators = $di->get(DocumentValidator::class)->makeValidators($di, $this->key1);
        foreach ($validators as $property => $rules) {
            foreach ($rules as $rule) {
                $validation->add($property, $rule);
            }
        }

        return $validation->validate($this->bsonSerialize());
    }

    public function setAttributes(array $data = []): self
    {
        isset($data['key1']) && $this->key1 = trim($data['key1']);
        isset($data['key2']) && $this->key2 = trim($data['key2']);
        isset($data['value']) && $this->value = $data['value'];
        if (isset($data['createAt'])) {
            try {
                $dt = new DateTime('now', new DateTimeZone(DateTimeHelper::TIMEZONE_UTC));
                $this->createAt = $dt->setTimestamp($data['createAt']);
            } catch (\Exception $e) {
            }
        }

        return $this;
    }

    public function __construct(array $data = [])
    {
        $this->setAttributes($data);
    }

    public function bsonSerialize(): array
    {
        return [
            'key1' => $this->key1,
            'key2' => $this->key2,
            'value' => $this->value,
            'createAt' => $this->createAt->getTimestamp(),
        ];
    }

    public function bsonUnserialize(array $data): void
    {
        $this->setAttributes($data);
    }
}
