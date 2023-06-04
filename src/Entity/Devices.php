<?php

namespace App\Entity;

use App\Controller\DevicePreviewPictureController;
use App\Controller\DeviceImageController;
use App\Controller\DeviceImageUploadController;
use App\Controller\DeviceImageDeleteController;
use App\Controller\UploadImageDeleteController;
use App\Entity\DeviceXDeviceOption;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devices
 *
 * @ORM\Table(
 *    name="devices",
 *    uniqueConstraints={
 *      @ORM\UniqueConstraint(name="unique_device_name", columns={"name"}),
 *      @ORM\UniqueConstraint(name="unique_device_seo_link", columns={"seo_link"})
 *    },
 *    indexes={
 *      @ORM\Index(name="device_creator", columns={"creator"}),
 *      @ORM\Index(name="device_id", columns={"id"}),
 *      @ORM\Index(name="device_updater", columns={"updater"})
 *    }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *   normalizationContext={"groups"={"read"}},
 *   denormalizationContext={"groups"={"write"}},
 *   collectionOperations={
 *     "get"={"method"="GET"},
 *     "post"={"method"="POST"},
 *     "get_images_for_device" = {
 *        "method" = "GET",
 *        "path" = "/devices/{id}/images",
 *        "controller" = DeviceImageController::class,
 *        "read" = false,
 *        "openapi_context" = {
 *          "parameters" = {
 *           {
 *             "name" = "id",
 *             "in" = "path",
 *             "description" = "id of device",
 *             "type" = "integer",
 *             "required" = false,
 *             "example"= 1,
 *           },
 *         },
 *       },
 *     },
 *     "delete_upload_image" = {
 *       "method" = "DELETE",
 *       "path" = "/uploads/image/{fileName}",
 *       "controller" = UploadImageDeleteController::class
 *     },
 *     "delete_device_image" = {
 *       "method" = "DELETE",
 *       "path" = "/devices/{id}/image/{fileName}",
 *       "controller" = DeviceImageDeleteController::class
 *     },
 *     "post_upload_images" = {
 *       "method" = "POST",
 *       "path" = "/devices/images",
 *       "controller" = DeviceImageUploadController::class,
 *       "deserialize" = false,
 *       "openapi_context" = {
 *         "requestBody" = {
 *           "description" = "File upload to an existing resource (device)",
 *           "required" = true,
 *           "content" = {
 *             "multipart/form-data" = {
 *               "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                   "file" = {
 *                     "type" = "string",
 *                     "format" = "binary",
 *                     "description" = "Upload images for device",
 *                   },
 *                 },
 *               },
 *             },
 *           },
 *         },
 *       },
 *     }
 *   },
 *   itemOperations={
 *     "get",
 *     "delete",
 *     "put",
 *     "get_by_slug" = {
 *       "method" = "GET",
 *       "path" = "/superhero/{slug}",
 *       "controller" = SuperheroBySlug::class,
 *       "read"=false,
 *       "openapi_context" = {
 *         "parameters" = {
 *           {
 *             "name" = "slug",
 *             "in" = "path",
 *             "description" = "The slug of your superhero",
 *             "type" = "string",
 *             "required" = true,
 *             "example"= "superman",
 *           },
 *         },
 *       },
 *     },
 *   }
 * )
 */
class Devices
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_link", type="string", length=250, nullable=false)
     * @Groups({"read", "write"})
     */
    private $seoLink;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_picture_path", type="string", length=250, nullable=false)
     * @Groups({"read", "write"})
     */
    private $previewPicturePath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @Groups({"read"})
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Groups({"read"})
     */
    private $updated;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $creator;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $updater;

    /**
     * @var Collection|DeviceOption[]
     *
     * @ORM\OneToMany(targetEntity=DeviceXDeviceOption::class, mappedBy="device", cascade={"ALL"}, orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $deviceXDeviceOptions;

    public function __construct()
    {
        $this->deviceXDeviceOptions = new ArrayCollection();
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
     * @param Users  $creator
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
}
