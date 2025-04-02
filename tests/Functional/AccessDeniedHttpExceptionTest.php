<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\ChainUserProvider;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class AccessDeniedHttpExceptionTest extends FunctionalTestCase
{
    public function testAccessDeniedHttpException(): void
    {
        $client = static::createClient();
        /** @var ChainUserProvider $chainUserProvider */
        $chainUserProvider = self::getContainer()->get('security.user_providers');
        $user = $chainUserProvider->loadUserByIdentifier('user1');
        $client->loginUser($user);
        $client->request('GET', '/api/access-denied-request');

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_FORBIDDEN,
            'forbidden',
            'Access Denied error: Forbidden'
        );
    }
}
