<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Request;

use VisualCraft\RestBaseBundle\Constants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class ApiZoneRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request): bool
    {
        return $request->attributes->get(Constants::API_ZONE_ATTRIBUTE, false) === true;
    }
}
