<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserProvider
{
    public function __construct(private TokenStorageInterface $tokenStorage, private EntityManagerInterface $entityManager)
    {
    }

    public function provide(): Users|null
    {
        if ($this->tokenStorage->getToken() === null) {
            return null;
        }

        return $this->tokenStorage->getToken() !== null && $this->tokenStorage->getToken()->getUser() instanceof Users ?
            $this->tokenStorage->getToken()->getUser() :
            $this->loadUserByToken($this->tokenStorage->getToken()->getUser());
    }

    /**
     * Load user by given jwt token user.
     */
    private function loadUserByToken(JWTUser $jWTUser): Users
    {
        return $this->entityManager->getRepository(Users::class)->findOneBy(
            ['email' => $jWTUser->getUserIdentifier()]
        );
    }
}
