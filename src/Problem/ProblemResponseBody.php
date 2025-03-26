<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class ProblemResponseBody
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $details;

    public function __construct(string $title, string $type, int $statusCode, array $details)
    {
        $this->title = $title;
        $this->type = $type;
        $this->statusCode = $statusCode;
        $this->details = $details;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
