<?php

declare(strict_types=1);

namespace App\Service\Authentication;

use App\Entity\Users;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

final class LogoutHandler implements LogoutHandlerInterface, LogoutSuccessHandlerInterface
{
    /** @var Connection */
    private $databaseConnection;

    public function __construct(Connection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    public function logout(Request $request, Response $response, TokenInterface $token): void
    {
        $authenticatedUser = $token->getUser();

        if (null === $authenticatedUser) {
            return;
        }

        $this->databaseConnection->delete('refresh_tokens', ['username' => $authenticatedUser->getUserIdentifier()]);
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @return Response
     */
    public function onLogoutSuccess(Request $request)
    {
        return new RedirectResponse('/');
    }
}