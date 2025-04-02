<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Exceptions;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @deprecated
 */
class ValidationErrorException extends \RuntimeException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violationList;

    public function __construct(
        ConstraintViolationListInterface $violationList,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->violationList = $violationList;
    }

    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
