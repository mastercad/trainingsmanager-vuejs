<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\ApiHelper;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\Attributes\Depends;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use function file_get_contents;

final class ExerciseImageUploadControllerTest extends ApiTestCase
{
    private ApiHelper $apiHelper;
    private const EXERCISES_ENDPOINT = '/api/exercises/images';

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
                            'exercises' => []
                        ]
                    ]
                ],
                'uploads' => [],
                'dummyImage.png' => file_get_contents(__DIR__ . '/../fixtures/dummyImage.png')
            ],
            $this->virtualFileSystem
        );

        vfsStreamWrapper::setRoot($this->virtualFileSystem);
    }

    /** this test is just to ensure environment is like expected */
    public function testImageFoldersAreEmpty(): void
    {
        self::assertTrue($this->virtualFileSystem->hasChild('images/content/dynamic/exercises'), 'dynamic image folder for exercises should exists!');
        self::assertTrue($this->virtualFileSystem->hasChild('uploads'), 'uploads folder should exists!');
    }

    #[Depends('testImageFoldersAreEmpty')]
    public function testFileEntryIsMissing(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        self::assertFileExists($this->virtualFileSystem->getChild('dummyImage.png')->url());

        $file = $this->generateUploadFile();

        $response = $this->apiHelper->upload(
            self::EXERCISES_ENDPOINT,
            [
                'wrongKey' => [$file]
            ]
        );

        self::assertResponseStatusCodeSame(400, 'response should successfully after upload new image!');
        self::assertIsArray($response);
        self::assertArrayHasKey('error', $response);
        self::assertSame('"file" is required', $response['error']);

        self::assertFileExists($this->virtualFileSystem->getChild('dummyImage.png')->url(), 'This file should not moved!');
    }

    #[Depends('testImageFoldersAreEmpty')]
    public function testImageUpload(): void
    {
        $this->apiHelper->loginUser('test@example.com');

        self::assertFileExists($this->virtualFileSystem->getChild('dummyImage.png')->url());

        $file = $this->generateUploadFile();

        $this->apiHelper->upload(
            self::EXERCISES_ENDPOINT,
            [
                'exerciseImage' => [$file]
            ]
        );

        self::assertResponseIsSuccessful('response should successfully after upload new image!');

        self::assertTrue($this->virtualFileSystem->hasChild('uploads/test@example.com'), 'user specific uploads folder should exists!');
        self::assertCount(1, $this->virtualFileSystem->getChild('uploads/test@example.com')->getChildren(), 'user specific uploads folder should not empty!');
        self::assertNull($this->virtualFileSystem->getChild('dummyImage.png'), 'This file should moved!');
    }

    private function generateUploadFile(): UploadedFile
    {
        return new UploadedFile(
            $this->virtualFileSystem->getChild('dummyImage.png')->url(),
            'device_image.png',
            'image/png',
        );
    }
}
