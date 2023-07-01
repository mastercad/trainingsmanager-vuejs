<?php

declare(strict_types=1);

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\ApiHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GeneralApplicationTest extends ApiTestCase
{
  private ApiHelper $apiHelper;

  public function setUp(): void
  {
    $client = self::createClient();
    $container = self::getContainer();

    $this->apiHelper = $container->get(ApiHelper::class);
    $this->apiHelper->init($this, $container, $client);
  }

  public function testLogin(): void
  {
      $this->apiHelper->request(Request::METHOD_GET, '/api/devices');
      self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED, 'request without authorization should result in 401.');

      $this->apiHelper->loginUser('test@example.com');

      $this->apiHelper->request(Request::METHOD_GET, '/api/devices');
      self::assertResponseIsSuccessful('simple get request with authorization should work without any restrictions or problems.');
  }
}
