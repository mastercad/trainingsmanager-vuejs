<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Controller\DeviceImageController;
use App\Controller\DeviceImageDeleteController;
use App\Controller\DeviceImageUploadController;
use App\Controller\UploadImageDeleteController;
use ArrayObject;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Devices
 */
#[ApiResource(
    formats: [
        'json',
        'jsonld',
        'html',
        'jsonhal'
    ],
    normalizationContext: ['groups' => [self::READ_GROUP]],
    denormalizationContext: ['groups' => [self::WRITE_GROUP]],
    operations: [
        new Get(
            uriTemplate: '/devices/{id}/images',
            requirements: ['id' => '\d+'],
            controller: DeviceImageController::class,
            read: false,
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'id of device',
                        'type' => 'integer',
                        'required' => true,
                        'example' => 1
                    ]
                ]
            ]
        ),
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/devices/images',
            controller: DeviceImageUploadController::class,
            deserialize: false,
            openapi: new Operation(
                requestBody: new RequestBody(
                    description:  'File upload to an existing resource (device)',
                    required: true,
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                        'description' => 'image path for image',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            ),
        ),
        new Post(),
        new Patch(),
        new Put(),
        new Delete(),
        new Delete(
            uriTemplate: '/uploads/image/{fileName}',
            controller: UploadImageDeleteController::class,
        ),
        new Delete(
            uriTemplate: '/devices/{id}/image/{fileName}',
            controller: DeviceImageDeleteController::class,
        ),
    ],
)]
#[ORM\Table(name: 'devices')]
#[ORM\Index(name: 'device_id', columns: ['id'])]
#[ORM\Index(name: 'device_creator', columns: ['creator'])]
#[ORM\Index(name: 'device_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_device_name', columns: ['name'])]
#[ORM\UniqueConstraint(name: 'unique_device_seo_link', columns: ['seo_link'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(PropertyFilter::class)]
class Devices
{
    private const READ_GROUP = 'device:read';
    private const WRITE_GROUP = 'device:write';

    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups([self::READ_GROUP, self::WRITE_GROUP])]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: true)]
    #[Assert\NotBlank]
    #[Groups([self::READ_GROUP, self::WRITE_GROUP])]
    private string $name;

    #[ORM\Column(name: 'seo_link', type: 'string', length: 250, nullable: false)]
    #[Groups([self::READ_GROUP, self::WRITE_GROUP])]
    private string $seoLink;

    #[ORM\Column(name: 'preview_picture_path', type: 'string', length: 250, nullable: false)]
    #[Groups([self::READ_GROUP, self::WRITE_GROUP])]
    private string $previewPicturePath;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups([self::READ_GROUP])]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups([self::READ_GROUP])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups([self::READ_GROUP])]
    private Users $updater;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups([self::READ_GROUP])]
    private DateTime|null $updated = null;

    /** @var Collection|DeviceOption[] */
    #[ORM\OneToMany(targetEntity: DeviceXDeviceOption::class, mappedBy: 'device', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups([self::READ_GROUP, self::WRITE_GROUP])]
    private Collection $deviceXDeviceOptions;

    /** @var Collection|DeviceOption[] */
    #[ORM\OneToMany(targetEntity: DeviceXDeviceGroup::class, mappedBy: 'device', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups([self::READ_GROUP, self::WRITE_GROUP])]
    private Collection $deviceXDeviceGroups;

    public function __construct()
    {
        $this->deviceXDeviceOptions = new ArrayCollection();
        $this->deviceXDeviceGroups = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of seoLink
     */
    public function getSeoLink(): string
    {
        return $this->seoLink;
    }

    /**
     * Set the value of seoLink
     */
    public function setSeoLink(string $seoLink): self
    {
        $this->seoLink = $seoLink;

        return $this;
    }

    /**
     * Get the value of previewPicturePath
     */
    public function getPreviewPicturePath(): string
    {
        return $this->previewPicturePath;
    }

    /**
     * Set the value of previewPicturePath
     */
    public function setPreviewPicturePath(string $previewPicturePath): self
    {
        $this->previewPicturePath = $previewPicturePath;

        return $this;
    }

    /**
     * Get the value of created
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Set the value of created
     */
    public function setCreated(DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updated
     */
    public function getUpdated(): DateTime|null
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     */
    public function setUpdated(DateTime|null $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of creator
     */
    public function getCreator(): Users|null
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     */
    public function setCreator(Users $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of updater
     */
    public function getUpdater(): Users
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     */
    public function setUpdater(Users $updater): self
    {
        $this->updater = $updater;

        return $this;
    }

    /** @return DeviceXDeviceOption[] */
    public function getDeviceXDeviceOptions(): Collection
    {
        return $this->deviceXDeviceOptions;
    }

    public function addDeviceXDeviceOption(DeviceXDeviceOption $deviceXDeviceOption): void
    {
        if ($this->deviceXDeviceOptions->contains($deviceXDeviceOption)) {
            return;
        }

        $this->deviceXDeviceOptions->add($deviceXDeviceOption);
        $deviceXDeviceOption->setDevice($this);
    }

    public function removeDeviceXDeviceOption(DeviceXDeviceOption $deviceXDeviceOption): void
    {
        if (! $this->deviceXDeviceOptions->contains($deviceXDeviceOption)) {
            return;
        }

        $this->deviceXDeviceOptions->removeElement($deviceXDeviceOption);
        $deviceXDeviceOption->setDevice(null);
    }

    /** @return DeviceXDeviceGroup[] */
    public function getDeviceXDeviceGroups(): Collection
    {
        return $this->deviceXDeviceGroups;
    }

    public function addDeviceXDeviceGroup(DeviceXDeviceGroup $deviceXDeviceGroup): void
    {
        if ($this->deviceXDeviceGroups->contains($deviceXDeviceGroup)) {
            return;
        }

        $this->deviceXDeviceGroups->add($deviceXDeviceGroup);
        $deviceXDeviceGroup->setDevice($this);
    }

    /** @param DeviceXDeviceGroup $deviceXDeviceOption */
    public function removeDeviceXDeviceGroup(DeviceXDeviceGroup $deviceXDeviceGroup): void
    {
        if (! $this->deviceXDeviceGroups->contains($deviceXDeviceGroup)) {
            return;
        }

        $this->deviceXDeviceGroups->removeElement($deviceXDeviceGroup);
        $deviceXDeviceGroup->setDevice(null);
    }
}
