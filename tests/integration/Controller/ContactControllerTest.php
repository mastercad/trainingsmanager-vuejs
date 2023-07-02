<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\ApiHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContactControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;

    public function setUp(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $this->apiHelper = $container->get(ApiHelper::class);
        $this->apiHelper->init($this, $container, $client);
    }

    public function testCreateContact(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        // test that sending no message will result to a bad request HTTP code.
        $response = $this->apiHelper->request(Request::METHOD_POST, '/api/contacts');
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        // test that sending a correct message will result to a created HTTP code.
        $response = $this->apiHelper->request(Request::METHOD_POST, '/api/contacts', [
            'firstName' => 'Test First Name',
            'lastName' => 'Test Last Name',
            'emailAddress' => 'Test@email.de'
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testFindAllContacts(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_GET, '/api/contacts');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertArrayHasKey('hydra:member', $response);
        self::assertCount(1, $response['hydra:member']);
        self::assertArrayHasKey('@type', $response['hydra:member'][0]);
        self::assertSame('Contacts', $response['hydra:member'][0]['@type']);
        self::assertArrayHasKey('firstName', $response['hydra:member'][0]);
        self::assertSame('Test First Name', $response['hydra:member'][0]['firstName']);
        self::assertArrayHasKey('lastName', $response['hydra:member'][0]);
        self::assertSame('Test Last Name', $response['hydra:member'][0]['lastName']);
        self::assertArrayHasKey('emailAddress', $response['hydra:member'][0]);
        self::assertSame('Test@email.de', $response['hydra:member'][0]['emailAddress']);
    }
}
