<?php declare(strict_types=1);

namespace Storage\Service\Factorys\Validators;


use Phalcon\Di\DiInterface;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Storage\Service\Exceptions\ServiceRuntimeException;

class DocumentValidatorFactory implements ValidatorFactoryInterface
{
    public const TYPE_PHONE = 'phone';
    public const TYPE_EMAIL = 'email';

    public const TYPE_SUPPORTED = [
        self::TYPE_PHONE,
        self::TYPE_EMAIL,
    ];

    public function makeValidators(DiInterface $di, string $type): array
    {
        $validators = [];

        switch ($type) {
            case self::TYPE_PHONE:
                $validators[] = $di->get(Regex::class, [[
                    'message' => 'The telephone is required.',
                    'pattern' => '/^\+?[0-9]{3}-?[0-9]{6,12}$/',
                    'allowEmpty' => false,
                ]]);
                break;
            case self::TYPE_EMAIL:
                $validators[] = $di->get(PresenceOf::class, [[
                    'message' => 'The email is required.',
                ]]);
                $validators[] = $di->get(Email::class, [[
                    'message' => 'The email is not valid.',
                ]]);
                break;
            default:
                throw new ServiceRuntimeException('key1 value is not supported.');
        }

        return $validators;
    }
}
