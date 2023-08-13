<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Exercises;
use App\Service\SeoLinkHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class SeoLinkHandlerTest extends TestCase
{
    private SeoLinkHandler $seoLinkHandler;
    private EntityManagerInterface|MockObject $entityManagerMock;
    private EntityRepository|MockObject $repositoryMock;

    public function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->repositoryMock = $this->createMock(EntityRepository::class);
        $this->seoLinkHandler = new SeoLinkHandler($this->entityManagerMock);
    }

    /** @dataProvider handleSeoLinkWithInvalidObjectDataProvider */
    public function testHandleSeoLinkForCreateWithInvalidObject(mixed $object): void
    {
        self::assertNull($this->seoLinkHandler->handleSeoLinkForCreate($object));
    }

    /** @dataProvider handleSeoLinkWithInvalidObjectDataProvider */
    public function testHandleSeoLinkForUpdateWithInvalidObject(mixed $object): void
    {
        self::assertNull($this->seoLinkHandler->handleSeoLinkForUpdate($object));
    }

    public function handleSeoLinkWithInvalidObjectDataProvider(): Generator
    {
        yield 'pass string instead of object' => [
            'object' => 'sdadsadas'
        ];

        yield 'pass stdClass instead of valid entity' => [
            'object' => new stdClass()
        ];
    }

    /**
     * @param mixed[] $repoReturnValueMap
     *
     * @dataProvider handleSeoLinkForCreateDataProvider
     */
    public function testHandleSeoLinkForCreate(
        string $currentName,
        string $currentSeoLink,
        int $repositoryCallCount,
        array $repoReturnValueMap,
        string $expectedSeoLink
    ): void {
        $entity = new Exercises();
        $entity->setName($currentName);
        $entity->setSeoLink($currentSeoLink);

        $this->repositoryMock
            ->expects(self::exactly($repositoryCallCount))
            ->method('findOneBy')
            ->will($this->returnValueMap($repoReturnValueMap));

        $this->entityManagerMock
            ->expects(self::exactly($repositoryCallCount))
            ->method('getRepository')
            ->with(Exercises::class)
            ->willReturn($this->repositoryMock);

        self::assertSame($expectedSeoLink, $this->seoLinkHandler->handleSeoLinkForCreate($entity));
    }

    /**
     * @param mixed[] $repoReturnValueMap
     *
     * @dataProvider handleSeoLinkForUpdateDataProvider
     */
    public function testHandleSeoLinkForUpdate(
        string $currentName,
        string $currentSeoLink,
        int $repositoryCallCount,
        int $findCallCount,
        array $repoReturnValueMap,
        string $expectedSeoLink
    ): void {
        $entity = new Exercises();
        $entity->setName($currentName);
        $entity->setSeoLink($currentSeoLink);

        $this->repositoryMock
            ->expects(self::exactly($findCallCount))
            ->method('findOneBy')
            ->will($this->returnValueMap($repoReturnValueMap));

        $this->entityManagerMock
            ->expects(self::exactly($repositoryCallCount))
            ->method('getRepository')
            ->with(Exercises::class)
            ->willReturn($this->repositoryMock);

        self::assertSame($expectedSeoLink, $this->seoLinkHandler->handleSeoLinkForUpdate($entity));
    }

    public function handleSeoLinkForCreateDataProvider(): Generator
    {
        yield 'handle seo link without existing name' => [
            'currentName' => '',
            'currentSeoLink' => '',
            'repositoryCallCount' => 1,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => ''],
                    null, ''
                ]
            ],
            'expectedSeoLink' => ''
        ];

        yield 'handle seo link without existing seoLink' => [
            'currentName' => 'test',
            'currentSeoLink' => '',
            'repositoryCallCount' => 1,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'test'],
                    null, ''
                ]
            ],
            'expectedSeoLink' => 'test'
        ];

        yield 'handle seo link with existing seoLink, but not own' => [
            'currentName' => 'test',
            'currentSeoLink' => '',
            'repositoryCallCount' => 2,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'test'],
                    null, 'test'
                ],
                [
                    ['seoLink' => 'test_1'],
                    null, ''
                ]
            ],
            'expectedSeoLink' => 'test_1'
        ];
    }

    public function handleSeoLinkForUpdateDataProvider(): Generator
    {
        yield 'handle seo link without existing name' => [
            'currentName' => '',
            'currentSeoLink' => '',
            'repositoryCallCount' => 1,
            'findCallCount' => 1,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => ''],
                    null, ''
                ]
            ],
            'expectedSeoLink' => ''
        ];

        yield 'handle seo link without existing seoLink' => [
            'currentName' => 'test',
            'currentSeoLink' => '',
            'repositoryCallCount' => 1,
            'findCallCount' => 1,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'test'],
                    null, ''
                ]
            ],
            'expectedSeoLink' => 'test'
        ];

        yield 'handle seo link with existing seoLink and name not changed' => [
            'currentName' => 'test',
            'currentSeoLink' => 'test',
            'repositoryCallCount' => 1,
            'findCallCount' => 0,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'test'],
                    null, 'test'
                ]
            ],
            'expectedSeoLink' => 'test'
        ];

        yield 'handle seo link with existing seoLink and name changed, old seo link does not exists' => [
            'currentName' => 'new test',
            'currentSeoLink' => 'test',
            'repositoryCallCount' => 1,
            'findCallCount' => 1,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'new_test'],
                    null, ''
                ]
            ],
            'expectedSeoLink' => 'new_test'
        ];

        yield 'handle seo link with existing seoLink and name changed, new seo link does not exists' => [
            'currentName' => 'new test',
            'currentSeoLink' => 'test',
            'repositoryCallCount' => 1,
            'findCallCount' => 1,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'new_test'],
                    null, ''
                ]
            ],
            'expectedSeoLink' => 'new_test'
        ];

        yield 'handle seo link with existing seoLink and name changed, new seo link exists' => [
            'currentName' => 'new test',
            'currentSeoLink' => 'test',
            'repositoryCallCount' => 2,
            'findCallCount' => 2,
            'repoReturnValueMap' => [
                [
                    ['seoLink' => 'new_test'],
                    null, 'new_test'
                ],
                [
                    ['seoLink' => 'new_test_1'],
                    null, ''
                ]
            ],
            'expectedSeoLink' => 'new_test_1'
        ];
    }
}
