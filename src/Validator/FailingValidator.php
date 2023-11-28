<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException;

/**
 * @deprecated
 */
class FailingValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param mixed $value
     * @param Constraint|Constraint[] $constraints
     * @throws ValidationErrorException
     */
    public function validate($value, $constraints = null): void
    {
        $violations = $this->validator->validate($value, $constraints);

        if (\count($violations) > 0) {
            throw new ValidationErrorException($violations);
        }
    }
}
