<?php declare(strict_types=1);

namespace Storage\Service\Factorys\Validators;


use Phalcon\Di\DiInterface;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class DocumentValidator
{
    public const TYPE_PHONE = 'phone';
    public const TYPE_EMAIL = 'email';

    public const TYPE_SUPPORTED = [
        self::TYPE_PHONE,
        self::TYPE_EMAIL,
    ];

    private function getForPhone(DiInterface $di): array
    {
        return [
            $di->get(Regex::class, [[
                'message' => 'The telephone is required.',
                'pattern' => '/^\+?[0-9]{3}-?[0-9]{6,12}$/',
                'allowEmpty' => false,
            ]]),
        ];
    }

    private function getForEmail(DiInterface $di): array
    {
        return [
            $di->get(PresenceOf::class, [[
                'message' => 'The email is required.',
            ]]),
            $di->get(Email::class, [[
                'message' => 'The email is not valid.',
            ]]),
        ];
    }

    public function makeValidators(DiInterface $di, string $type): array
    {
        $validators = [];

        $validators['key1'][] = $di->get(Callback::class, [[
            'callback' => static fn ($data) => in_array(
                $data['key1'],
                DocumentValidator::TYPE_SUPPORTED,
                true
            ),
            'message' => 'key1 value is not supported.',
        ]]);

        switch ($type) {
            case self::TYPE_PHONE:
                $validators['key2'] = $this->getForPhone($di);
                break;
            case self::TYPE_EMAIL:
                $validators['key2'] = $this->getForEmail($di);
                break;
        }

        return $validators;
    }
}
