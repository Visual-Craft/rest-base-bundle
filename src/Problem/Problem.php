<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem;

class Problem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $details;

    /**
     * @var array
     */
    private $headers;

    public function __construct(string $title, int $statusCode, string $type)
    {
        $this->title = $title;
        $this->statusCode = $statusCode;
        $this->type = $type;
        $this->details = [];
        $this->headers = [];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $value): self
    {
        $this->details = $value;

        return $this;
    }

    public function addDetails(string $key, $value): void
    {
        $this->details[$key] = $value;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $value): self
    {
        $this->headers = $value;

        return $this;
    }
}
