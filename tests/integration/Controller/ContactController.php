<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Safe\Exceptions\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function count;

final class ContactControllerTest extends AbstractController
{
    /**
     * @throws JsonException
     */
    public function testCreateContact(): void
    {
        // test that sending no message will result to a bad request HTTP code.
        $this->JSONRequest(Request::METHOD_POST, '/api/contacts');
        $this->assertJSONResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
        // test that sending a correct message will result to a created HTTP code.
        $this->JSONRequest(Request::METHOD_POST, '/api/contacts', [
            'firstName' => 'Test First Name',
            'lastName' => 'Test Last Name',
            'emailAddress' => 'Test@email.de'
        ]);
        $this->assertJSONResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    /**
     * @throws JsonException
     */
    public function testFindAllContacts(): void
    {
        $this->client->request(Request::METHOD_GET, '/api/contacts');
        $response = $this->client->getResponse();
        $content = $this->assertJSONResponse($response, Response::HTTP_OK);
        $this->assertEquals(1, count($content));
    }
}