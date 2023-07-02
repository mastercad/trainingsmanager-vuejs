<?php

declare(strict_types=1);

namespace App\Service\Authentication;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

final class LogoutHandler implements LogoutHandlerInterface, LogoutSuccessHandlerInterface
{
    public function __construct(private Connection $databaseConnection)
    {
    }

    public function logout(Request $request, Response $response, TokenInterface $token): void
    {
        $authenticatedUser = $token->getUser();

        if ($authenticatedUser === null) {
            return;
        }

        $this->databaseConnection->delete('refresh_tokens', ['username' => $authenticatedUser->getUserIdentifier()]);
    }

    /**
     * Creates a Response object to send upon a successful logout.
     */
    public function onLogoutSuccess(Request $request): Response
    {
        return new RedirectResponse('/');
    }
}
