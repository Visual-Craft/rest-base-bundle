<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ErrorController as SymfonyErrorController;
use VisualCraft\RestBaseBundle\Problem\ProblemResponseFactory;
use VisualCraft\RestBaseBundle\Request\ApiZoneRequestMatcher;

class ErrorController
{
    /**
     * @var SymfonyErrorController
     */
    private $errorController;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ProblemResponseFactory
     */
    private $problemResponseFactory;

    /**
     * @var ApiZoneRequestMatcher
     */
    private $apiZoneRequestMatcher;

    public function __construct(
        SymfonyErrorController $errorController,
        RequestStack $requestStack,
        ProblemResponseFactory $problemResponseFactory,
        ApiZoneRequestMatcher $apiZoneRequestMatcher
    ) {
        $this->errorController = $errorController;
        $this->requestStack = $requestStack;
        $this->problemResponseFactory = $problemResponseFactory;
        $this->apiZoneRequestMatcher = $apiZoneRequestMatcher;
    }

    public function __invoke(\Throwable $exception): Response
    {
        if ($this->apiZoneRequestMatcher->matches($this->requestStack->getCurrentRequest())) {
            return $this->problemResponseFactory->create($exception);
        }

        return $this->errorController->__invoke($exception);
    }

    public function preview(Request $request, int $code): Response
    {
        return $this->errorController->preview($request, $code);
    }
}
