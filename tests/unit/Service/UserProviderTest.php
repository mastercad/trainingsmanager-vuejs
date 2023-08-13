<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Users;
use App\Service\UserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserProviderTest extends TestCase
{
    private UserProvider $userProvider;
    private TokenStorageInterface|MockObject $tokenStorageMock;
    private EntityManagerInterface|MockObject $entityManagerMock;
    private const USER_IDENTIFIER = 'USER_IDENTIFIER';

    public function setUp(): void
    {
        $this->tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $this->userProvider = new UserProvider($this->tokenStorageMock, $this->entityManagerMock);
    }

    /** @dataProvider withMissingOrWrongTokenDataProvider */
    public function testWithMissingOrWrongToken(mixed $token): void
    {
        $this->entityManagerMock->expects(self::never())
            ->method('getRepository');

        self::assertNull($this->userProvider->provide());
    }

    public function testWithMissingUser(): void
    {
        $this->entityManagerMock->expects(self::never())
            ->method('getRepository');

        $this->prepareToken(null, 2, 1);

        self::assertNull($this->userProvider->provide());
    }

    public function testWithValidUser(): void
    {
        $user = new Users();

        $this->entityManagerMock->expects(self::never())
            ->method('getRepository');

        $this->prepareToken($user, 2, 1);

        self::assertSame($user, $this->userProvider->provide());
    }

    public function testLoadMissingUserFromJwtToken(): void
    {
        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->expects(self::exactly(1))
            ->method('findOneBy')
            ->with(['email' => self::USER_IDENTIFIER])
            ->willReturn(null);

        $this->entityManagerMock->expects(self::exactly(1))
            ->method('getRepository')
            ->willReturn($repositoryMock);

        $userMock = $this->createMock(JWTUser::class);
        $userMock->expects(self::exactly(1))
            ->method('getUserIdentifier')
            ->willReturn(self::USER_IDENTIFIER);

        $this->prepareToken($userMock, 3, 1);

        self::assertNull($this->userProvider->provide());
    }

    public function testLoadExistingUserFromJwtToken(): void
    {
        $user = new Users();

        $repositoryMock = $this->createMock(EntityRepository::class);
        $repositoryMock->expects(self::exactly(1))
            ->method('findOneBy')
            ->with(['email' => self::USER_IDENTIFIER])
            ->willReturn($user);

        $this->entityManagerMock->expects(self::exactly(1))
            ->method('getRepository')
            ->willReturn($repositoryMock);

        $userMock = $this->createMock(JWTUser::class);
        $userMock->expects(self::exactly(1))
            ->method('getUserIdentifier')
            ->willReturn(self::USER_IDENTIFIER);

        $this->prepareToken($userMock, 3, 1);

        self::assertSame($user, $this->userProvider->provide());
    }

    public function withMissingOrWrongTokenDataProvider(): Generator
    {
        yield 'no token' => [
            'token' => null
        ];

        yield 'wrong object' => [
            'token' => new stdClass()
        ];

        yield 'wrong token type' => [
            'token' => 'token'
        ];
    }

    private function prepareToken(mixed $user, int $getUserCallCount, int $getTokenCallCount): void
    {
        $tokenMock = $this->createMock(TokenInterface::class);

        $tokenMock->expects(self::exactly($getUserCallCount))
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorageMock->expects(self::exactly($getTokenCallCount))
            ->method('getToken')
            ->willReturn($tokenMock);
    }
}
