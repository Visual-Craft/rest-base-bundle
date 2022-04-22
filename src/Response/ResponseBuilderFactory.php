<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Response;

use Symfony\Component\Serializer\SerializerInterface;
use VisualCraft\RestBaseBundle\Serializer\FormatRegistry;

class ResponseBuilderFactory
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FormatRegistry
     */
    private $formatRegistry;

    public function __construct(SerializerInterface $serializer, FormatRegistry $formatRegistry)
    {
        $this->serializer = $serializer;
        $this->formatRegistry = $formatRegistry;
    }

    public function create($data): ResponseBuilder
    {
        return new ResponseBuilder($this->serializer, $this->formatRegistry, $data);
    }
}
