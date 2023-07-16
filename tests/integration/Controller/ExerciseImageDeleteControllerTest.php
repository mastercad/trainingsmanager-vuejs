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

final class ExerciseImageDeleteControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;
    private const EXERCISES_ENDPOINT = '/api/exercises/{id}/image/{fileName}';

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

    public function testDeleteExistingImageFromWrongExercise(): void
    {
        $this->apiHelper->loginUser('another_user_test@example.com');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $this->prepareUrl(2, 'imageForExercise1_2.png'));

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(2, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
        self::assertArrayHasKey('message', $response);
        self::assertSame('File does not exists!', $response['message']);
        self::assertFileExists($this->virtualFileSystem->getChild('images/content/dynamic/exercises/1/imageForExercise1_2.png')->url());
    }

    public function testDeleteExistingImageFromCorrectExercise(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        $url = $this->prepareUrl(4, 'imageForExercise4_1021.bmp');

        $response = $this->apiHelper->request(Request::METHOD_DELETE, $url);

        self::assertResponseIsSuccessful();

        self::assertIsArray($response);
        self::assertCount(1, $response);
        self::assertArrayHasKey('success', $response);
        self::assertTrue($response['success']);
        self::assertNull($this->virtualFileSystem->getChild('images/content/dynamic/exercises/4/imageForExercise1_2.png'));
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
                self::EXERCISES_ENDPOINT
            )
        );
    }
}
