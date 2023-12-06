<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestContentTypeException;
use VisualCraft\RestBaseBundle\Serializer\FormatRegistry;
use VisualCraft\RestBaseBundle\Validator\FailingValidator;

/**
 * @deprecated since v0.3, use Symfony\Component\HttpKernel\Attribute\MapRequestPayload instead
 * @psalm-suppress DeprecatedClass
 */
class RequestBodyDeserializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FormatRegistry
     */
    private $formatRegistry;

    private FailingValidator $validator;

    public function __construct(
        SerializerInterface $serializer,
        FormatRegistry $formatRegistry,
        FailingValidator $validator
    ) {
        $this->serializer = $serializer;
        $this->formatRegistry = $formatRegistry;
        $this->validator = $validator;
    }

    /**
     * @return mixed
     */
    public function deserialize(Request $request, string $type, ?string $forcedFormat = null)
    {
        if ($forcedFormat !== null) {
            if (!$this->formatRegistry->isFormatSupported($forcedFormat)) {
                throw new \LogicException('Unsupported format');
            }

            $format = $forcedFormat;
        } else {
            $contentType = $request->headers->get('Content-Type');

            if (!$contentType) {
                throw new InvalidRequestContentTypeException(
                    'Missing Content-Type',
                    InvalidRequestContentTypeException::CODE_MISSING,
                    $this->formatRegistry->getSupportedMimeTypes()
                );
            }

            $format = $this->formatRegistry->getFormatByMimeType($contentType);

            if ($format === null) {
                throw new InvalidRequestContentTypeException(
                    'Unsupported Content-Type',
                    InvalidRequestContentTypeException::CODE_UNSUPPORTED,
                    $this->formatRegistry->getSupportedMimeTypes()
                );
            }
        }

        try {
            $data = $this->serializer->deserialize(
                $request->getContent(),
                $type,
                $format,
                [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false]
            );
        } catch (UnexpectedValueException $e) {
            throw new InvalidRequestBodyFormatException('Unable to deserialize data since it contains unexpected value', 0, $e);
        } catch (ExtraAttributesException $e) {
            throw new InvalidRequestBodyFormatException('Unable to deserialize data since it contains extra attributes', 0, $e);
        }

        $this->validator->validate($data);

        return $data;
    }
}
