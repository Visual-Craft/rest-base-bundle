<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Security;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException;

class RestBaseValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($data): void
    {
        $violations = $this->validator->validate($data);

        if (\count($violations) > 0) {
            throw new ValidationErrorException($violations);
        }
    }
}
