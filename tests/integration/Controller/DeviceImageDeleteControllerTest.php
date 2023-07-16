<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\ApiHelper;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use Symfony\Component\HttpFoundation\Request;

use function base64_encode;
use function str_replace;

final class DeviceImageDeleteControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;
    private const DEVICES_ENDPOINT = '/api/devices/{id}/image/{fileName}';

    private vfsStreamDirectory $virtualFileSystem;

    public function setUp(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $this->apiHelper = $container->get(ApiHelper::class);
        $this->apiHelper->init($this, $container, $client);

        vfsStreamWrapper::register();

        $this->virtualFileSystem = new vfsStreamDirectory('public', 0777);

        vfsStream::create(
            [
                'images' => [
                    'content' => [
                        'dynamic' => [
                            'devices' => [
                                1 => [
                                    'imageForDevice1_1.jpg' => '',
                                    'imageForDevice1_2.png' => '',
                                ],
                                2 => [
                                    'imageForDevice2_1.png' => '',
                                    'imageForDevice2_20.jpg' => ''
                                ],
                                4 => [
                                    'imageForDevice4_10.png' => '',
                                    'imageForDevice4_1021.bmp' =>  ''
                                ]
                            ]
                        ]
                    ]
                ],
                'uploads' => [
                    'test@example.com' => [
                        'freshUploadedImage1.png' => '',
                        'freshUploadedImage2.png' => ''
                    ]
                ]
            ],
            $this->virtualFileSystem
        );

        vfsStreamWrapper::setRoot($this->virtualFileSystem);
    }

    public function testDeleteExistingImageFromWrongDevice(): void
    {
        $this->apiHelper->loginUser('another_user_test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $this->prepareUrl(2, 'imageForDevice1_2.png'));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
        self::assertArrayHasKey('message', $response);
        self::assertSame('File does not exists!', $response['message']);
    }

    public function testDeleteExistingImageFromCorrectDevice(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $url = $this->prepareUrl(4, 'imageForDevice4_1021.bmp');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $url);

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
    }

    public function testDeleteNotExistingImage(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $this->prepareUrl(3, 'image_does_not_exists.png'));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
        self::assertArrayHasKey('message', $response);
        self::assertSame('File does not exists!', $response['message']);
    }

    private function prepareUrl(int $id, string $fileName): string
    {
        return str_replace(
            '{fileName}',
            base64_encode($fileName),
            str_replace(
                '{id}',
                (string) $id,
                self::DEVICES_ENDPOINT
            )
        );
    }
}
