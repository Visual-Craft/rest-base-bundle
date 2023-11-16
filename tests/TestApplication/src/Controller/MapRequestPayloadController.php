<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Dto\MapRequestPayloadDto;

class MapRequestPayloadController extends AbstractController
{
    /** @psalm-suppress UndefinedAttributeClass, ParseError */
    public function __invoke(
        #[MapRequestPayload]
        MapRequestPayloadDto $mapRequestPayloadDto): JsonResponse
    {
        return new JsonResponse([]);
    }
}
