<?php declare(strict_types=1);

namespace Storage\Service\Factorys\Validators;

use Phalcon\Di\DiInterface;
use Phalcon\Validation\ValidatorInterface;

interface ValidatorFactoryInterface
{
    /**
     * @param DiInterface $di
     * @param string $type
     * @return ValidatorInterface[]
     */
    public function makeValidators(DiInterface $di, string $type): array;
}
