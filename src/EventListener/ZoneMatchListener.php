<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\EventListener;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use VisualCraft\RestBaseBundle\Constants;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class ZoneMatchListener
{
    /**
     * @var iterable|RequestMatcherInterface[]
     * @psalm-var iterable<array-key, RequestMatcherInterface>
     */
    private $requestMatchers;

    /**
     * @param iterable|RequestMatcherInterface[] $requestMatchers
     * @psalm-param iterable<array-key, RequestMatcherInterface> $requestMatchers
     */
    public function __construct(iterable $requestMatchers)
    {
        $this->requestMatchers = $requestMatchers;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $zoneMatched = false;

        foreach ($this->requestMatchers as $requestMatcher) {
            if ($requestMatcher->matches($request)) {
                $zoneMatched = true;

                break;
            }
        }

        $request->attributes->set(Constants::API_ZONE_ATTRIBUTE, $zoneMatched);
    }
}
