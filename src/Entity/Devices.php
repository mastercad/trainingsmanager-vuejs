<?php

namespace App\Entity;

use App\Controller\DeviceImageController;
use App\Controller\DeviceImageUploadController;
use App\Controller\DeviceImageDeleteController;
use App\Controller\UploadImageDeleteController;
use App\Entity\DeviceXDeviceOption;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devices
 */
#[ApiResource(
  validationContext: ['groups' => ['device:write']],
  normalizationContext: ['groups' => ['device:read']],
  denormalizationContext: ['groups' => ['device:write']],
  operations: [
    new Get(),
    new Get(
      uriTemplate: "/devices/{id}/images",
      requirements: ['id' => '\d+'],
      controller: DeviceImageController::class,
      read: false,
      openapiContext: [
        'parameters' => [
          [
            "name" => "id",
            "in" => "path",
            "description" => "id of device",
            "type" => "integer",
            "required" => true,
            "example"=> 1,
          ],
        ],
      ],
    ),
    new GetCollection(),
    new Post(),
    new Post(
      uriTemplate: "/devices/images",
      controller: DeviceImageUploadController::class,
      deserialize: false,
      openapiContext: [
        "requestBody" => [
          "description" => "File upload to an existing resource (device)",
          "required" => true,
          "content" => [
            "multipart/form-data" => [
              "schema" => [
                "type" => "object",
                "properties" => [
                  "file" => [
                    "type" => "string",
                    "format" => "binary",
                    "description" => "image path name for device"
                  ]
                ]
              ]
            ]
          ]
        ]
      ]
    ),
    new Patch(),
    new Put(),
    new Delete(),
    new Delete(
      uriTemplate: "/uploads/image/{fileName}",
      controller: UploadImageDeleteController::class
    ),
    new Delete(
      uriTemplate: "/devices/{id}/image/{fileName}",
      controller: DeviceImageDeleteController::class
    )
  ]
)]
#[ORM\Table(name: 'devices')]
#[ORM\Index(name: 'device_id', columns: ['id'])]
#[ORM\Index(name: 'device_creator', columns: ['creator'])]
#[ORM\Index(name: 'device_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_device_name', columns: ['name'])]
#[ORM\UniqueConstraint(name: 'unique_device_seo_link', columns: ['seo_link'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Devices
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['device:read', 'device:write'])]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: false)]
    #[Assert\NotBlank(groups: [ 'device:write' ])]
    #[Groups(['device:read', 'device:write'])]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'seo_link', type: 'string', length: 250, nullable: false)]
    #[Groups(['device:read', 'device:write'])]
    private $seoLink;

    /**
     * @var string
     */
    #[ORM\Column(name: 'preview_picture_path', type: 'string', length: 250, nullable: false)]
    #[Groups(['device:read', 'device:write'])]
    private $previewPicturePath;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['device:read'])]
    private $creator;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['device:read'])]
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['device:read'])]
    private $updater;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['device:read'])]
    private $updated;

    /**
     * @var Collection|DeviceOption[]
     */
    #[ORM\OneToMany(targetEntity: DeviceXDeviceOption::class, mappedBy: 'device', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups(['device:read', 'device:write'])]
    private $deviceXDeviceOptions;

    /**
     * @var Collection|DeviceOption[]
     */
    #[ORM\OneToMany(targetEntity: DeviceXDeviceGroup::class, mappedBy: 'device', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups(['device:read', 'device:write'])]
    private $deviceXDeviceGroups;

    public function __construct()
    {
        $this->deviceXDeviceOptions = new ArrayCollection();
        $this->deviceXDeviceGroups = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of seoLink
     *
     * @return  string
     */
    public function getSeoLink()
    {
        return $this->seoLink;
    }

    /**
     * Set the value of seoLink
     *
     * @param  string  $seoLink
     *
     * @return  self
     */
    public function setSeoLink(string $seoLink)
    {
        $this->seoLink = $seoLink;

        return $this;
    }

    /**
     * Get the value of previewPicturePath
     *
     * @return  string
     */
    public function getPreviewPicturePath()
    {
        return $this->previewPicturePath;
    }

    /**
     * Set the value of previewPicturePath
     *
     * @param  string  $previewPicturePath
     *
     * @return  self
     */
    public function setPreviewPicturePath(string $previewPicturePath)
    {
        $this->previewPicturePath = $previewPicturePath;

        return $this;
    }

    /**
     * Get the value of created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the value of created
     *
     * @param \DateTime  $created
     *
     * @return  self
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updated
     *
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     *
     * @param \DateTime|null  $updated
     *
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of creator
     *
     * @return Users
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @param Users $creator
     *
     * @return self
     */
    public function setCreator(Users $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of updater
     *
     * @return Users
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     *
     * @param Users $updater
     *
     * @return  self
     */
    public function setUpdater(Users $updater)
    {
        $this->updater = $updater;

        return $this;
    }

    public function getDeviceXDeviceOptions()
    {
        return $this->deviceXDeviceOptions;
    }

    public function addDeviceXDeviceOption(DeviceXDeviceOption $deviceXDeviceOption)
    {
        if ($this->deviceXDeviceOptions->contains($deviceXDeviceOption)) {
            return;
        }

        $this->deviceXDeviceOptions->add($deviceXDeviceOption);
        $deviceXDeviceOption->setDevice($this);
    }

    /**
     * @param DeviceXDeviceOption $deviceXDeviceOption
     */
    public function removeDeviceXDeviceOption(DeviceXDeviceOption $deviceXDeviceOption)
    {
        if (!$this->deviceXDeviceOptions->contains($deviceXDeviceOption)) {
            return;
        }

        $this->deviceXDeviceOptions->removeElement($deviceXDeviceOption);
        $deviceXDeviceOption->setDevice(null);
    }

    public function getDeviceXDeviceGroups()
    {
        return $this->deviceXDeviceGroups;
    }

    public function addDeviceXDeviceGroup(DeviceXDeviceGroup $deviceXDeviceGroup)
    {
        if ($this->deviceXDeviceGroups->contains($deviceXDeviceGroup)) {
            return;
        }

        $this->deviceXDeviceGroups->add($deviceXDeviceGroup);
        $deviceXDeviceGroup->setDevice($this);
    }

    /**
     * @param DeviceXDeviceGroup $deviceXDeviceOption
     */
    public function removeDeviceXDeviceGroup(DeviceXDeviceGroup $deviceXDeviceGroup)
    {
        if (!$this->deviceXDeviceGroups->contains($deviceXDeviceGroup)) {
            return;
        }

        $this->deviceXDeviceGroups->removeElement($deviceXDeviceGroup);
        $deviceXDeviceGroup->setDevice(null);
    }
}
