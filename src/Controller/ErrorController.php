<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ErrorController as SymfonyErrorController;
use VisualCraft\RestBaseBundle\Constants;
use VisualCraft\RestBaseBundle\Problem\ProblemResponseFactory;

class ErrorController
{
    /**
     * @var SymfonyErrorController
     */
    private $errorController;

    /**
     * @var ProblemResponseFactory
     */
    private $problemResponseFactory;

    public function __construct(SymfonyErrorController $errorController, ProblemResponseFactory $problemResponseFactory)
    {
        $this->errorController = $errorController;
        $this->problemResponseFactory = $problemResponseFactory;
    }

    public function __invoke(\Throwable $exception, Request $request): Response
    {
        /** @psalm-suppress ReservedWord, RedundantCondition */
        if ($request->attributes->get(Constants::API_ZONE_ATTRIBUTE, false)) {
            return $this->problemResponseFactory->create($exception);
        }

        return $this->errorController->__invoke($exception);
    }

    public function preview(Request $request, int $code): Response
    {
        return $this->errorController->preview($request, $code);
    }
}
