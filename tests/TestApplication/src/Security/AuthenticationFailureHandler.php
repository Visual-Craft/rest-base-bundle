<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use VisualCraft\RestBaseBundle\Problem\ProblemResponseFactory;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var ProblemResponseFactory
     */
    private $problemResponseFactory;

    public function __construct(ProblemResponseFactory $problemResponseFactory)
{
    $this->problemResponseFactory = $problemResponseFactory;
}

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->problemResponseFactory->create($exception);
    }
}
