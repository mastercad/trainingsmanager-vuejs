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

final class UploadImageDeleteControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;
    private const EXERCISES_ENDPOINT = '/api/uploads/image/{fileName}';

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
                            'exercises' => [
                                1 => [
                                    'imageForExercise1_1.jpg' => '',
                                    'imageForExercise1_2.png' => '',
                                ],
                                2 => [
                                    'imageForExercise2_1.png' => '',
                                    'imageForExercise2_20.jpg' => ''
                                ],
                                4 => [
                                    'imageForExercise4_10.png' => '',
                                    'imageForExercise4_1021.bmp' =>  ''
                                ]
                            ]
                        ]
                    ]
                ],
                'uploads' => [
                    'test@example.com' => [
                        'freshUploadedImage1.png' => '',
                        'freshUploadedImage2.png' => ''
                    ],
                    'another_test@example.com' => [
                        'anotherFreshUploadedImage1.png' => '',
                        'freshUploadedImage2.png' => ''
                    ]
                ]
            ],
            $this->virtualFileSystem
        );

        vfsStreamWrapper::setRoot($this->virtualFileSystem);
    }

    public function testDeleteUploadedImageForAnotherUser(): void
    {
        $this->apiHelper->loginUser('another_user_test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $this->prepareUrl('freshUploadedImage2.png'));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
        self::assertFileExists($this->virtualFileSystem->getChild('uploads/test@example.com/freshUploadedImage2.png')->url());
    }

    public function testDeleteUploadedExistingImage(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $this->prepareUrl('freshUploadedImage2.png'));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
        self::assertNull($this->virtualFileSystem->getChild('uploads/test@example.com/freshUploadedImage2.png'));
    }

    private function prepareUrl(string $fileName): string
    {
        return str_replace(
            '{fileName}',
            base64_encode($fileName),
            self::EXERCISES_ENDPOINT
        );
    }
}
