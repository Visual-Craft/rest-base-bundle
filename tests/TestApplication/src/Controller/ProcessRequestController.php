<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Request\RequestBodyDeserializer;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Dto\TestDto;

class ProcessRequestController extends AbstractController
{
    /**
     * @var RequestBodyDeserializer
     */
    private $deserializer;

    public function __construct(RequestBodyDeserializer $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var TestDto $dto */
        $testDto = $this->deserializer->deserialize($request, TestDto::class);

        return new JsonResponse([
            'field1' => $testDto->getField1(),
            'field2' => $testDto->getField2(),
            'field3' => $testDto->getField3(),
        ]);
    }
}
