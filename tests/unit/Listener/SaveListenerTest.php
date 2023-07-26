<?php

declare(strict_types=1);

namespace App\Tests\Unit\Listener;

use App\Entity\Devices;
use App\Entity\Exercises;
use App\Entity\Users;
use App\Listener\SaveListener;
use App\Service\FileUploader;
use App\Service\SeoLinkHandler;
use App\Service\UserProvider;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

final class SaveListenerTest extends TestCase
{
    private SaveListener $saveListener;
    private UserProvider|MockObject $userProviderMock;
    private SeoLinkHandler|MockObject $seoLinkHandlerMock;
    private FileUploader|MockObject $fileUploaderMock;

    public function setUp(): void
    {
        $this->userProviderMock = $this->createMock(UserProvider::class);
        $this->seoLinkHandlerMock = $this->createMock(SeoLinkHandler::class);
        $this->fileUploaderMock = $this->createMock(FileUploader::class);

        $this->saveListener = new SaveListener(
            $this->userProviderMock,
            $this->fileUploaderMock,
            $this->seoLinkHandlerMock
        );
    }

    public function testPrePersistWithWrongObjectType(): void
    {
        $object = new stdClass();
        $args = $this->createPrePersistEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::never())
            ->method('handleSeoLinkForCreate');

        $this->userProviderMock->expects(self::never())
            ->method('provide');

        self::assertNull($this->saveListener->prePersist($args));
    }

    public function testPrePersistWithUser(): void
    {
        $object = new Users();
        $args = $this->createPrePersistEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::never())
            ->method('handleSeoLinkForCreate');

        $this->userProviderMock->expects(self::never())
            ->method('provide');

        self::assertNull($this->saveListener->prePersist($args));
    }

    public function testPrePersistWithAlreadyExistingCreator(): void
    {
        $object = new Exercises();
        $user = new Users();

        $object->setCreator($user);

        $this->seoLinkHandlerMock->expects(self::never())
            ->method('handleSeoLinkForCreate');

        $this->userProviderMock->expects(self::never())
            ->method('provide');

        $args = $this->createPrePersistEventArgs($object);

        self::assertNull($this->saveListener->prePersist($args));
        self::assertSame($user, $object->getCreator());
    }

    public function testPrePersistWithoutCreator(): void
    {
        $object = new Exercises();
        $object->setPreviewPicturePath('');

        $uuid = Uuid::uuid4();
        $user = new Users();
        $user->setId($uuid);
        $user->setEmail('test@example.com');

        $args = $this->createPrePersistEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::once())
            ->method('handleSeoLinkForCreate');

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($user);

        self::assertNull($this->saveListener->prePersist($args));
        self::assertSame($user, $object->getCreator());
        self::assertInstanceOf(DateTime::class, $object->getCreated());
        self::assertSame('', $object->getPreviewPicturePath());
    }

    public function testPrePersistWithoutCreatorAndPreviewPicture(): void
    {
        $object = new Exercises();
        $object->setPreviewPicturePath('/uploads/test@example.com/previewTestPicture.png');

        $uuid = Uuid::uuid4();
        $user = new Users();
        $user->setId($uuid);
        $user->setEmail('test@example.com');

        $args = $this->createPrePersistEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::once())
            ->method('handleSeoLinkForCreate');

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($user);

        self::assertNull($this->saveListener->prePersist($args));
        self::assertSame($user, $object->getCreator());
        self::assertInstanceOf(DateTime::class, $object->getCreated());
        self::assertSame('previewTestPicture.png', $object->getPreviewPicturePath());
    }

    public function testPreUpdateWithWrongObjectType(): void
    {
        $object = new stdClass();
        $args = $this->createPreUpdateEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::never())
            ->method('handleSeoLinkForUpdate');

        $this->userProviderMock->expects(self::never())
            ->method('provide');

        self::assertNull($this->saveListener->preUpdate($args));
    }

    public function testPreUpdateWithUser(): void
    {
        $object = new Users();
        $args = $this->createPreUpdateEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::never())
            ->method('handleSeoLinkForUpdate');

        $this->userProviderMock->expects(self::never())
            ->method('provide');

        self::assertNull($this->saveListener->preUpdate($args));
    }

    public function testPreUpdateWithAlreadyExistingUpdater(): void
    {
        $object = new Exercises();
        $object->setPreviewPicturePath('');

        $uuid = Uuid::uuid4();
        $user = new Users();
        $user->setId($uuid);
        $user->setEmail('test@example.com');

        $object->setUpdater($user);

        $this->seoLinkHandlerMock->expects(self::once())
            ->method('handleSeoLinkForUpdate');

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($user);

        $args = $this->createPreUpdateEventArgs($object);

        self::assertNull($this->saveListener->preUpdate($args));
        self::assertSame($user, $object->getUpdater());
        self::assertInstanceOf(DateTime::class, $object->getUpdated());
    }

    public function testPreUpdateWithoutUpdater(): void
    {
        $object = new Exercises();
        $object->setPreviewPicturePath('');

        $uuid = Uuid::uuid4();
        $user = new Users();
        $user->setId($uuid);
        $user->setEmail('test@example.com');

        $args = $this->createPreUpdateEventArgs($object);

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($user);

        $this->seoLinkHandlerMock->expects(self::once())
            ->method('handleSeoLinkForUpdate');

        self::assertNull($this->saveListener->preUpdate($args));
        self::assertSame($user, $object->getUpdater());
        self::assertInstanceOf(DateTime::class, $object->getUpdated());
        self::assertSame('', $object->getPreviewPicturePath());
    }

    public function testPreUpdateWithoutUpdaterAndPreviewPicture(): void
    {
        $object = new Exercises();
        $object->setPreviewPicturePath('/uploads/test@example.com/previewTestPicture.png');

        $uuid = Uuid::uuid4();
        $user = new Users();
        $user->setId($uuid);
        $user->setEmail('test@example.com');

        $args = $this->createPreUpdateEventArgs($object);

        $this->seoLinkHandlerMock->expects(self::once())
            ->method('handleSeoLinkForUpdate');

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($user);

        self::assertNull($this->saveListener->preUpdate($args));
        self::assertSame($user, $object->getUpdater());
        self::assertInstanceOf(DateTime::class, $object->getUpdated());
        self::assertSame('previewTestPicture.png', $object->getPreviewPicturePath());
    }

    public function testPostPersistWithoutUser(): void
    {
        $object = new Exercises();

        $this->fileUploaderMock->expects(self::never())
            ->method('moveAllUploadedFiles');

        $this->saveListener->postPersist($this->createPostPersistArgs($object));
    }

    public function testPostUpdateWithoutUser(): void
    {
        $object = new Exercises();

        $this->fileUploaderMock->expects(self::never())
            ->method('moveAllUploadedFiles');

        $this->saveListener->postUpdate($this->createPostUpdateArgs($object));
    }

    /** @dataProvider postActionWithValidDataDataProvider */
    public function testPostPersistWithUser(
        string $userIdentifier,
        string $objectClass,
        string $expectedTargetPath
    ): void {
        $object = new $objectClass();
        $userMock = $this->createMock(Users::class);
        $userMock->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($userIdentifier);

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($userMock);

        $object->setCreator($userMock);
        $object->setId(12345);

        $this->fileUploaderMock->expects(self::once())
            ->method('moveAllUploadedFiles')
            ->with($userIdentifier, $expectedTargetPath, 12345);

        $this->saveListener->postPersist($this->createPostPersistArgs($object));
    }

    /** @dataProvider postActionWithValidDataDataProvider */
    public function testPostUpdateWithUser(
        string $userIdentifier,
        string $objectClass,
        string $expectedTargetPath
    ): void {
        $object = new $objectClass();
        $userMock = $this->createMock(Users::class);
        $userMock->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($userIdentifier);

        $this->userProviderMock->expects(self::once())
            ->method('provide')
            ->willReturn($userMock);

        $object->setCreator($userMock);
        $object->setId(12345);

        $this->fileUploaderMock->expects(self::once())
            ->method('moveAllUploadedFiles')
            ->with($userIdentifier, $expectedTargetPath, 12345);

        $this->saveListener->postUpdate($this->createPostUpdateArgs($object));
    }

    public function testPostPersistWithWrongObject(): void
    {
        $object = new stdClass();

        $this->fileUploaderMock->expects(self::never())
            ->method('moveAllUploadedFiles');

        $this->saveListener->postPersist($this->createPostPersistArgs($object));
    }

    public function testPostUpdateWithWrongObject(): void
    {
        $object = new stdClass();

        $this->fileUploaderMock->expects(self::never())
            ->method('moveAllUploadedFiles');

        $this->saveListener->postUpdate($this->createPostUpdateArgs($object));
    }

    public function postActionWithValidDataDataProvider(): Generator
    {
        yield 'call with exercise and test@exmaple.com' => [
            'userIdentifier' => 'test@example.com',
            'objectClass' => Exercises::class,
            'expectedTargetPath' => 'exercises'
        ];

        yield 'call with device and another_user@exmaple.com' => [
            'userIdentifier' => 'another_user@example.com',
            'objectClass' => Devices::class,
            'expectedTargetPath' => 'devices'
        ];
    }

    private function createPrePersistEventArgs(object $object): PrePersistEventArgs
    {
        return new PrePersistEventArgs($object, $this->createMock(EntityManagerInterface::class));
    }

    private function createPreUpdateEventArgs(object $object): PreUpdateEventArgs
    {
        $changeSet = [];

        return new PreUpdateEventArgs($object, $this->createMock(EntityManagerInterface::class), $changeSet);
    }

    private function createPostPersistArgs(object $object): PostPersistEventArgs
    {
        return new PostPersistEventArgs($object, $this->createMock(EntityManagerInterface::class));
    }

    private function createPostUpdateArgs(object $object): PostUpdateEventArgs
    {
        $changeSet = [];

        return new PostUpdateEventArgs($object, $this->createMock(EntityManagerInterface::class), $changeSet);
    }
}
