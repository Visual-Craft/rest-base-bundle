<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestContentTypeException;
use VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException;
use VisualCraft\RestBaseBundle\Serializer\FormatRegistry;

class RequestBodyDeserializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var FormatRegistry
     */
    private $formatRegistry;

    public function __construct(
        SerializerInterface $serializer,
        FormatRegistry $formatRegistry,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->formatRegistry = $formatRegistry;
        $this->validator = $validator;
    }

    public function deserialize(Request $request, string $type, ?string $forcedFormat = null)
    {
        $format = null;

        if ($forcedFormat !== null) {
            if (!$this->formatRegistry->isFormatSupported($forcedFormat)) {
                throw new \LogicException('Unsupported format');
            }

            $format = $forcedFormat;
        } else {
            if (!$request->headers->has('Content-Type')) {
                throw new InvalidRequestContentTypeException(
                    'Missing Content-Type',
                    InvalidRequestContentTypeException::CODE_MISSING,
                    $this->formatRegistry->getSupportedMimeTypes()
                );
            }

            $format = $this->formatRegistry->getFormatByMimeType($request->headers->get('Content-Type'));

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

        $violations = $this->validator->validate($data);

        if (\count($violations) > 0) {
            throw new ValidationErrorException($violations);
        }

        return $data;
    }
}
