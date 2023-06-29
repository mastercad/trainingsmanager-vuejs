<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ApiHelper
{
  private ContainerInterface $container;

  private EntityManagerInterface $entityManager;

  private ApiTestCase|WebTestCase $testCase;

  private Client|KernelBrowser $client;

  private ?string $token = null;

  private string $contentType = 'application/json';

  public function init(ApiTestCase|WebTestCase $testCase, TestContainer $container, Client|KernelBrowser $client)
  {
    $this->testCase = $testCase;
    $this->client = $client;
    $this->container = $container;
    $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
  }

  public function loginUser(string $login)
  {
    $user = $this->entityManager->getRepository(Users::class)->findOneBy(['login' => $login]);

    $this->client->loginUser($user);

    $json = $this->request(
      'POST',
      '/api/login_check', [
        'email' => 'test@example.com',
        'password' => '$3CR3T'
      ]
    );

    $this->testCase::assertResponseIsSuccessful();
    $this->testCase::assertArrayHasKey('token', $json);

    $this->token = $json['token'];

    return $this;
  }

  public function request(string $method, string $url, array $content = [])
  {
    $options = [
      'headers' => ['Content-Type' => $this->contentType],
      'json' => $content
    ];

    if (null !== $this->token) {
      $options['auth_bearer'] = $this->token;
    }

    return $this->client->request($method, $url, $options)->toArray(false);
  }

  public function setContentType(string $contentType)
  {
    $this->contentType = $contentType;

    return $this;
  }
}
