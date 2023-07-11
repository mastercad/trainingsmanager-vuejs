<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\ApiHelper;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use Symfony\Component\HttpFoundation\Request;

use function str_replace;

final class ExerciseImageControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;
    private const EXERCISES_ENDPOINT = '/api/exercises/{id}/images';

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
                    ]
                ]
            ],
            $this->virtualFileSystem
        );

        vfsStreamWrapper::setRoot($this->virtualFileSystem);
    }

    public function testGetImagesWithoutUploadedImages(): void
    {
        $this->apiHelper->loginUser('another_user_test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_GET, $this->prepareUrl(1));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertSame(
            [
                '/images/content/dynamic/exercises/1/imageForExercise1_1.jpg',
                '/images/content/dynamic/exercises/1/imageForExercise1_2.png'
            ],
            $response
        );
    }

    public function testGetImagesWithUploadedImages(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_GET, $this->prepareUrl(4));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(4, $response);
        self::assertSame(
            [
                '/images/content/dynamic/exercises/4/imageForExercise4_10.png',
                '/images/content/dynamic/exercises/4/imageForExercise4_1021.bmp',
                '/uploads/test@example.com/freshUploadedImage1.png',
                '/uploads/test@example.com/freshUploadedImage2.png'
            ],
            $response
        );
    }

    public function testGetImagesWithOnlyUploadedImages(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_GET, $this->prepareUrl(3));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertSame(
            [
                '/uploads/test@example.com/freshUploadedImage1.png',
                '/uploads/test@example.com/freshUploadedImage2.png'
            ],
            $response
        );
    }

    public function testGetImagesForNotExistingExercise(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_GET, $this->prepareUrl(1242));

        self::assertResponseStatusCodeSame(404);
        self::assertIsArray($response);
        self::assertArrayHasKey('error', $response);
        self::assertSame('Resource not found', $response['error']);
    }

    private function prepareUrl(int $id): string
    {
        return str_replace('{id}', (string) $id, self::EXERCISES_ENDPOINT);
    }
}
