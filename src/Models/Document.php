<?php declare(strict_types=1);

namespace Storage\Service\Models;


use DateTime;
use DateTimeZone;
use MongoDB\BSON\Unserializable;
use MongoDB\BSON\Serializable;
use Phalcon\Di\DiInterface;
use Phalcon\Messages\Messages;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Callback;
use Storage\Service\Factorys\Validators\DocumentValidatorFactory;
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

        $validation->add('key1', $di->get(Callback::class, [[
            'callback' => static fn ($data) => in_array(
                $data['key1'],
                DocumentValidatorFactory::TYPE_SUPPORTED,
                true
            ),
            'message' => 'key1 value is not supported.',
        ]]));

        $validators = $di->get(DocumentValidatorFactory::class)->makeValidators($di, $this->key1);
        foreach ($validators as $validator) {
            $validation->add('key2', $validator);
        }

        return $validation->validate(get_object_vars($this));
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

    /**
     * @return array
     */
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
