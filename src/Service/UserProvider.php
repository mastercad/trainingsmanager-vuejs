<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserProvider
{
    public function __construct(private TokenStorageInterface $tokenStorage, private EntityManagerInterface $entityManager)
    {
    }

    public function provide(): Users|null
    {
        $token = $this->tokenStorage->getToken();
        if (
            ! $token instanceof TokenInterface
            || (
                ! $token->getUser() instanceof Users
                && ! $token->getUser() instanceof JWTUser
            )
        ) {
            return null;
        }

        $user = $token->getUser();

        return $user instanceof Users ? $user : $this->loadUserByToken($user);
    }

    /**
     * Load user by given jwt token user.
     */
    private function loadUserByToken(JWTUser $jWTUser): Users|null
    {
        return $this->entityManager->getRepository(Users::class)->findOneBy(
            ['email' => $jWTUser->getUserIdentifier()]
        );
    }
}
