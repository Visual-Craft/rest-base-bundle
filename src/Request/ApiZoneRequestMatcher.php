<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use VisualCraft\RestBaseBundle\Constants;

class ApiZoneRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request): bool
    {
        /** @psalm-suppress ReservedWord */
        return $request->attributes->get(Constants::API_ZONE_ATTRIBUTE, false) === true;
    }
}
