<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Exceptions;

/**
 * @deprecated
 * @psalm-suppress DeprecatedClass
 */
class InvalidRequestContentTypeException extends InvalidRequestException
{
    public const CODE_MISSING = 1;
    public const CODE_UNSUPPORTED = 2;

    /**
     * @var array
     */
    private $validContentTypes;

    public function __construct(
        string $message = '',
        int $code = 0,
        array $validContentTypes = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->validContentTypes = $validContentTypes;
    }

    public function getValidContentTypes(): array
    {
        return $this->validContentTypes;
    }
}
