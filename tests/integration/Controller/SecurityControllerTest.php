<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Tests\ApiHelper;
use Symfony\Component\HttpFoundation\Request;

use function json_decode;
use function json_encode;

final class SecurityControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;

    private Client $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $container = self::getContainer();

        $this->apiHelper = $container->get(ApiHelper::class);
        $this->apiHelper->init($this, $container, $this->client);
    }

    public function testLoginWorks(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/login_check',
            [
                'headers' => ['CONTENT_TYPE' => 'application/json'],
                'body' => json_encode([
                    'email' => 'test@example.com',
                    'password' => '$3CR3T'
                ])
            ]
        );

        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertResponseFormatSame('json');

        $response = json_decode($this->client->getResponse()->getContent(), true);
        self::assertIsArray($response);
        self::assertArrayHasKey('token', $response);
        self::assertArrayHasKey('refresh_token', $response);
    }

    public function testLoginWithWrongUser(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/login_check',
            [
                'headers' => ['CONTENT_TYPE' => 'application/json'],
                'body' => json_encode([
                    'email' => 'test2@example.com',
                    'password' => '$3CR3T'
                ])
            ]
        );

        self::assertResponseStatusCodeSame(401);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertResponseFormatSame('json');

        $response = json_decode($this->client->getResponse()->getContent(false), true);
        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertArrayHasKey('code', $response);
        self::assertSame(401, $response['code']);
        self::assertArrayHasKey('message', $response);
        self::assertSame('Invalid credentials.', $response['message']);
    }

    public function testRefreshDoesNotWorkWithoutLogin(): void
    {
        $this->apiHelper->request(Request::METHOD_POST, '/api/refresh');

        self::assertResponseStatusCodeSame(403);
    }

    public function testRefreshWorks(): void
    {
        $this->apiHelper->loginUser('another_user_test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_POST, '/api/refresh');

        self::assertResponseIsSuccessful();
        self::assertIsArray($response);
        self::assertCount(3, $response);
        self::assertArrayHasKey('isAuthenticated', $response);
        self::assertTrue($response['isAuthenticated']);
        self::assertArrayHasKey('user', $response);

        $user = json_decode($response['user'], true);
        self::assertIsArray($user);
        self::assertArrayHasKey('id', $user);
        self::assertIsString($user['id']);
        self::assertArrayHasKey('token', $response);
        self::assertIsString($response['token']);
    }

    public function testLogoutWithoutLogin(): void
    {
        $this->client->request(Request::METHOD_GET, '/logout');

        self::assertResponseRedirects('http://localhost/');
    }

    public function testLogoutWithLogin(): void
    {
        $this->apiHelper->loginUser('another_user_test@example.com');

        $this->client->request(Request::METHOD_GET, '/logout');

        self::assertResponseRedirects('http://localhost/');
    }

    public function testRegisterWithMissingRequirements(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/register',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'multipart/form-data'
                ],
                'json' => [
                    'firstName' => 'test_user'
                ]
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);
        self::assertCount(2, $response);
        self::assertArrayHasKey('success', $response);
        self::assertFalse($response['success']);
        self::assertArrayHasKey('errors', $response);

        $errors = json_decode($response['errors'], true);
        self::assertCount(3, $errors);
        self::assertArrayHasKey('code', $errors);
        self::assertNull($errors['code']);
        self::assertArrayHasKey('message', $errors);
        self::assertSame('Validation Failed', $errors['message']);
        self::assertArrayHasKey('errors', $errors);

        $validationErrors = $errors['errors'];
        self::assertIsArray($validationErrors);
        self::assertCount(1, $validationErrors);
        self::assertArrayHasKey('children', $validationErrors);
        self::assertCount(5, $validationErrors['children']);
        self::assertArrayHasKey('email', $validationErrors['children']);
        self::assertIsArray($validationErrors['children']['email']);
        self::assertArrayHasKey('errors', $validationErrors['children']['email']);
        self::assertIsArray($validationErrors['children']['email']['errors']);
        self::assertArrayHasKey(0, $validationErrors['children']['email']['errors']);
        self::assertSame('This value should not be blank.', $validationErrors['children']['email']['errors'][0]);
        self::assertIsArray($validationErrors['children']['login']);
        self::assertArrayHasKey('errors', $validationErrors['children']['login']);
        self::assertIsArray($validationErrors['children']['login']['errors']);
        self::assertArrayHasKey(0, $validationErrors['children']['login']['errors']);
        self::assertSame('This value should not be blank.', $validationErrors['children']['login']['errors'][0]);
        self::assertIsArray($validationErrors['children']['lastName']);
        self::assertArrayHasKey('errors', $validationErrors['children']['lastName']);
        self::assertIsArray($validationErrors['children']['lastName']['errors']);
        self::assertArrayHasKey(0, $validationErrors['children']['lastName']['errors']);
        self::assertSame('This value should not be blank.', $validationErrors['children']['lastName']['errors'][0]);
        self::assertIsArray($validationErrors['children']['plainPassword']);
        self::assertArrayHasKey('children', $validationErrors['children']['plainPassword']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']);
        self::assertCount(2, $validationErrors['children']['plainPassword']['children']);
        self::assertArrayHasKey('first', $validationErrors['children']['plainPassword']['children']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['first']);
        self::assertArrayHasKey('errors', $validationErrors['children']['plainPassword']['children']['first']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['first']['errors']);
        self::assertArrayHasKey(0, $validationErrors['children']['plainPassword']['children']['first']['errors']);
        self::assertSame('This value should not be blank.', $validationErrors['children']['plainPassword']['children']['first']['errors'][0]);
        self::assertArrayHasKey('second', $validationErrors['children']['plainPassword']['children']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['second']);
    }

    public function testRegisterWithAllRequirementsButWrongSecondPassword(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/register',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'multipart/form-data'
                ],
                'json' => [
                    'firstName' => 'test_user',
                    'lastName' => 'lastName',
                    'email' => 'testRefgas@example.com',
                    'login' => 'testRefgas@example.com',
                    'plainPassword' => [
                        'first' => 'password',
                        'second' => 'anotherPassword'
                    ]
                ]
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);
        self::assertCount(2, $response);
        self::assertArrayHasKey('success', $response);
        self::assertFalse($response['success']);
        self::assertArrayHasKey('errors', $response);

        $errors = json_decode($response['errors'], true);
        self::assertCount(3, $errors);
        self::assertArrayHasKey('code', $errors);
        self::assertNull($errors['code']);
        self::assertArrayHasKey('message', $errors);
        self::assertSame('Validation Failed', $errors['message']);
        self::assertArrayHasKey('errors', $errors);

        $validationErrors = $errors['errors'];
        self::assertIsArray($validationErrors);
        self::assertCount(1, $validationErrors);
        self::assertArrayHasKey('children', $validationErrors);
        self::assertCount(5, $validationErrors['children']);
        self::assertArrayHasKey('email', $validationErrors['children']);
        self::assertIsArray($validationErrors['children']['email']);
        self::assertEmpty($validationErrors['children']['email']);
        self::assertIsArray($validationErrors['children']['login']);
        self::assertEmpty($validationErrors['children']['login']);
        self::assertIsArray($validationErrors['children']['firstName']);
        self::assertEmpty($validationErrors['children']['firstName']);
        self::assertIsArray($validationErrors['children']['lastName']);
        self::assertEmpty($validationErrors['children']['lastName']);
        self::assertIsArray($validationErrors['children']['plainPassword']);
        self::assertArrayHasKey('children', $validationErrors['children']['plainPassword']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']);
        self::assertCount(2, $validationErrors['children']['plainPassword']['children']);
        self::assertArrayHasKey('first', $validationErrors['children']['plainPassword']['children']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['first']);
        self::assertArrayHasKey('errors', $validationErrors['children']['plainPassword']['children']['first']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['first']['errors']);
        self::assertArrayHasKey(0, $validationErrors['children']['plainPassword']['children']['first']['errors']);
        self::assertSame('The values do not match.', $validationErrors['children']['plainPassword']['children']['first']['errors'][0]);
        self::assertArrayHasKey('second', $validationErrors['children']['plainPassword']['children']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['second']);
    }

    public function testRegisterWithAllRequirementsButAlreadyUsedEmail(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/register',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'multipart/form-data'
                ],
                'json' => [
                    'firstName' => 'test_user',
                    'lastName' => 'lastName',
                    'email' => 'test@example.com',
                    'login' => 'test@example.com',
                    'plainPassword' => [
                        'first' => 'password',
                        'second' => 'password'
                    ]
                ]
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertCount(2, $response);
        self::assertArrayHasKey('success', $response);
        self::assertFalse($response['success']);
        self::assertArrayHasKey('errors', $response);

        $errors = json_decode($response['errors'], true);
        self::assertCount(3, $errors);
        self::assertArrayHasKey('code', $errors);
        self::assertNull($errors['code']);
        self::assertArrayHasKey('message', $errors);
        self::assertSame('Validation Failed', $errors['message']);
        self::assertArrayHasKey('errors', $errors);

        $validationErrors = $errors['errors'];
        self::assertIsArray($validationErrors);
        self::assertCount(1, $validationErrors);
        self::assertArrayHasKey('children', $validationErrors);
        self::assertCount(5, $validationErrors['children']);
        self::assertArrayHasKey('email', $validationErrors['children']);
        self::assertIsArray($validationErrors['children']['email']);
        self::assertCount(1, $validationErrors['children']['email']);
        self::assertArrayHasKey('errors', $validationErrors['children']['email']);
        self::assertIsArray($validationErrors['children']['email']['errors']);
        self::assertArrayHasKey(0, $validationErrors['children']['email']['errors']);
        self::assertSame('This value is already used.', $validationErrors['children']['email']['errors'][0]);
        self::assertCount(1, $validationErrors['children']['email']['errors']);
        self::assertIsArray($validationErrors['children']['email']['errors']);
        self::assertIsArray($validationErrors['children']['login']);
        self::assertEmpty($validationErrors['children']['login']);
        self::assertIsArray($validationErrors['children']['firstName']);
        self::assertEmpty($validationErrors['children']['firstName']);
        self::assertIsArray($validationErrors['children']['lastName']);
        self::assertEmpty($validationErrors['children']['lastName']);
        self::assertIsArray($validationErrors['children']['plainPassword']);
        self::assertArrayHasKey('children', $validationErrors['children']['plainPassword']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']);
        self::assertCount(2, $validationErrors['children']['plainPassword']['children']);
        self::assertArrayHasKey('first', $validationErrors['children']['plainPassword']['children']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['first']);
        self::assertEmpty($validationErrors['children']['plainPassword']['children']['first']);
        self::assertArrayHasKey('second', $validationErrors['children']['plainPassword']['children']);
        self::assertIsArray($validationErrors['children']['plainPassword']['children']['second']);
        self::assertEmpty($validationErrors['children']['plainPassword']['children']['second']);
    }

    public function testRegisterWithAllRequirementsSuccess(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            '/register',
            [
                'headers' => [
                    'CONTENT_TYPE' => 'multipart/form-data'
                ],
                'json' => [
                    'firstName' => 'test_user',
                    'lastName' => 'lastName',
                    'email' => 'testUnique@example.com',
                    'login' => 'testUnique@example.com',
                    'plainPassword' => [
                        'first' => 'password',
                        'second' => 'password'
                    ]
                ]
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertIsArray($response);
        self::assertCount(3, $response);
        self::assertArrayHasKey('isAuthenticated', $response);
        self::assertTrue($response['isAuthenticated']);
        self::assertArrayHasKey('user', $response);

        $user = json_decode($response['user'], true);
        self::assertIsArray($user);
        self::assertArrayHasKey('id', $user);
        self::assertIsString($user['id']);
        self::assertArrayHasKey('token', $response);
        self::assertIsString($response['token']);
    }
}
