<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\FileUploader;
use Generator;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

use function file_get_contents;

final class FileUploaderTest extends TestCase
{
    private FileUploader $fileUploader;
    private LoggerInterface|MockObject $loggerMock;
    private SluggerInterface $sluggerMock;
    private UrlHelper $urlHelper;
    private vfsStreamDirectory $virtualFileSystem;

    public function setUp(): void
    {
        vfsStreamWrapper::register();

        $this->virtualFileSystem = new vfsStreamDirectory('public');

        vfsStream::create(
            [
                'images' => [
                    'content' => [
                        'dynamic' => [
                            'exercises' => []
                        ]
                    ]
                ],
                'uploads' => [
                    'some_folder' => [
                        'some_existing_file.txt' => ''
                    ]
                ],
                'dummyImage.png' => file_get_contents(__DIR__ . '/../../fixtures/dummyImage.png')
            ],
            $this->virtualFileSystem
        );

        vfsStreamWrapper::setRoot($this->virtualFileSystem);

        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->sluggerMock = new AsciiSlugger();
        $this->urlHelper = new UrlHelper(new RequestStack());

        $this->fileUploader = new FileUploader(
            $this->virtualFileSystem->url(),
            $this->virtualFileSystem->getChild('uploads')->url(),
            $this->sluggerMock,
            $this->urlHelper,
            $this->loggerMock
        );
    }

    /**
     * This test tests more ore less the UploadedFile functionality regarding not existing files,
     * its only to document this case is also covered.
     * */
    public function testUploadFileWithMissingFile(): void
    {
        self::expectException(FileNotFoundException::class);
        self::expectExceptionMessage('The file "vfs://uploads/file/does/not/exists.here" does not exist');

        $this->fileUploader->upload(new UploadedFile('vfs://uploads/file/does/not/exists.here', ''), 'TEST_IDENTIFIER');
    }

    public function testUploadExistingFile(): void
    {
        self::assertFileExists($this->virtualFileSystem->getChild('dummyImage.png')->url());

        $uploadedFile = new UploadedFile(
            $this->virtualFileSystem->getChild('dummyImage.png')->url(),
            'dummyImage.png',
            'image/png',
            null,
            true
        );

        $result = $this->fileUploader->upload($uploadedFile, 'TEST_IDENTIFIER');

        self::assertMatchesRegularExpression('/^dummyImage-[a-z0-9]+.png$/i', $result, 'Filename should unified with orig name prefixed!');
        self::assertFileExists($this->virtualFileSystem->getChild('uploads')->url() . '/TEST_IDENTIFIER/' . $result, 'New file should exist after upload!');
        self::assertNull($this->virtualFileSystem->getChild('dummyImage.png'), 'Orig file should removed!');
    }

    /** @dataProvider deleteDataProvider */
    #[DataProvider('deleteDataProvider')]
    public function testDelete(string $filePathName, bool $expectation): void
    {
        self::assertSame($expectation, $this->fileUploader->delete($filePathName, 'some_folder'));
    }

    public function deleteDataProvider(): Generator
    {
        yield 'test delete non existing file' => [
            'filePathName' => 'not_existing_file',
            'expectation' => true
        ];

        yield 'test delete existing file' => [
            'filePathName' => 'some_existing_file.txt',
            'expectation' => true
        ];
    }

    /** @dataProvider retrieveUrlDataProvider */
    #[DataProvider('retrieveUrlDataProvider')]
    public function testRetrieveUrl(string|null $fileName, bool $generateAbsolute, string|null $expectation): void
    {
        self::assertSame($expectation, $this->fileUploader->retrieveUrl($fileName, $generateAbsolute));
    }

    public function retrieveUrlDataProvider(): Generator
    {
        yield 'no filename => no url' => [
            'fileName' => null,
            'generateAbsolute' => true,
            'expectation' => null
        ];

        yield 'filename with absolute path' => [
            'fileName' => 'someFile.txt',
            'generateAbsolute' => true,
            'expectation' => 'vfs://public/uploads/someFile.txt'
        ];

        yield 'filename with relative path' => [
            'fileName' => 'someOtherFile.txt',
            'generateAbsolute' => false,
            'expectation' => '/uploads/someOtherFile.txt'
        ];
    }
}
