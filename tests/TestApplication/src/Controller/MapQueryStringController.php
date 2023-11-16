<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Dto\MapQueryStringDto;

class MapQueryStringController extends AbstractController
{
    /** @psalm-suppress UndefinedAttributeClass, ParseError */
    public function __invoke(
        #[MapQueryString]
        MapQueryStringDto $mapQueryStringDto): JsonResponse
    {
        return new JsonResponse([]);
    }
}
