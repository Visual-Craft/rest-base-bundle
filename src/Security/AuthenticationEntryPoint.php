<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use VisualCraft\RestBaseBundle\Problem\ProblemResponseFactory;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private ProblemResponseFactory $problemResponseFactory;

    public function __construct(ProblemResponseFactory $problemResponseFactory)
    {
        $this->problemResponseFactory = $problemResponseFactory;
    }

    #[\Override]
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return $this->problemResponseFactory->create($authException ?? new AuthenticationException('Authentication required'));
    }
}
