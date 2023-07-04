<?php

declare(strict_types=1);

namespace App\Tests\Integration\Entity;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\ApiHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DevicesTest extends ApiTestCase
{
    private ApiHelper $apiHelper;
    private const DEVICES_GET_ENDPOINT = '/api/devices';

    public function setUp(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $this->apiHelper = $container->get(ApiHelper::class);
        $this->apiHelper->init($this, $container, $client);
    }

    public function testLogin(): void
    {
        $this->apiHelper->request(Request::METHOD_GET, self::DEVICES_GET_ENDPOINT);
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED, 'request without authorization should result in 401.');

        $this->apiHelper->loginUser('test@example.com');

        $this->apiHelper->request(Request::METHOD_GET, self::DEVICES_GET_ENDPOINT);
        self::assertResponseIsSuccessful('simple get request with authorization should work without any restrictions or problems.');
    }

    public function testEmptyNameNotAccepted(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_POST, self::DEVICES_GET_ENDPOINT, ['Name' => ' ']);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        self::assertArrayHasKey('@type', $response);
        self::assertSame('ConstraintViolationList', $response['@type']);
        self::assertArrayHasKey('violations', $response);
        self::assertCount(1, $response['violations']);
        self::assertArrayHasKey('propertyPath', $response['violations'][0]);
        self::assertSame('name', $response['violations'][0]['propertyPath']);
        self::assertArrayHasKey('message', $response['violations'][0]);
        self::assertSame('This value should not be blank.', $response['violations'][0]['message']);
    }
}
