<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Response;

use VisualCraft\RestBaseBundle\Serializer\FormatRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseBuilder
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FormatRegistry
     */
    private $formatRegistry;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $format;

    /**
     * @var array
     */
    private $serializerContext;

    public function __construct(SerializerInterface $serializer, FormatRegistry $formatRegistry, $data)
    {
        $this->serializer = $serializer;
        $this->formatRegistry = $formatRegistry;
        $this->data = $data;
        $this->serializerContext = [];
        $this->response = new Response('', 200);
        $this->setFormat('json');
    }

    public function setFormat(string $value): self
    {
        if (!$this->formatRegistry->isFormatSupported($value)) {
            throw new \LogicException('Unsupported format');
        }

        $this->format = $value;

        return $this;
    }

    public function setStatusCode(int $value): self
    {
        $this->response->setStatusCode($value);

        return $this;
    }

    public function setHeaders(array $value): self
    {
        $this->response->headers->replace($value);

        return $this;
    }

    public function setHeader(string $name, array $value): self
    {
        $this->response->headers->set($name, $value);

        return $this;
    }

    public function addHeader(string $name, array $value): self
    {
        $this->response->headers->set($name, $value, false);

        return $this;
    }

    public function setSerializerContext(array $value): self
    {
        $this->serializerContext = $value;

        return $this;
    }

    public function addSerializerContext(string $name, array $value): self
    {
        $this->serializerContext[$name] = $value;

        return $this;
    }

    public function build(): Response
    {
        $serialized = $this->serializer->serialize($this->data, $this->format, $this->serializerContext);
        $this->response->setContent($serialized);
        $this->response->headers->set('Content-Type', $this->formatRegistry->getMimeTypeByFormat($this->format));

        return $this->response;
    }
}
