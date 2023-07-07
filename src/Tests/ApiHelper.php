<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ApiHelper
{
    private ContainerInterface $container;

    private EntityManagerInterface $entityManager;

    private ApiTestCase $testCase;

    private Client $client;

    private string|null $token = null;

    private string $contentType = 'application/json';

    public function init(ApiTestCase $testCase, TestContainer $container, Client $client): void
    {
        $this->testCase = $testCase;
        $this->client = $client;
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
    }

    public function loginUser(string $login): self
    {
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['login' => $login]);

        $this->client->loginUser($user);

        $json = $this->request(
            'POST',
            '/api/login_check',
            [
                'email' => 'test@example.com',
                'password' => '$3CR3T'
            ]
        );

        $this->testCase::assertResponseIsSuccessful();
        $this->testCase::assertArrayHasKey('token', $json);

        $this->token = $json['token'];

        return $this;
    }

    /**
     * @param mixed[] $content
     *
     * @return mixed[]
     */
    public function request(string $method, string $url, array $content = []): array
    {
        $options = [
            'headers' => ['Content-Type' => $this->contentType],
            'json' => $content
        ];

        if ($this->token !== null) {
            $options['auth_bearer'] = $this->token;
        }

        return $this->client->request($method, $url, $options)->toArray(false);
    }

    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }
}
