<?php

namespace App\Entity;

use App\Controller\ExerciseImageController;
use App\Controller\ExerciseImageDeleteController;
use App\Controller\ExerciseImageUploadController;
use App\Controller\UploadImageDeleteController;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;

/**
 * Exercises
 *
 * @ORM\Table(
 *    name="exercises",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="UN_exercise_name", columns={"name"}),
 *        @ORM\UniqueConstraint(name="UN_exercise_seo_link", columns={"seo_link"})
 *    },
 *    indexes={
 *        @ORM\Index(name="IDX_exercise_creator", columns={"creator"}),
 *        @ORM\Index(name="IDX_exercise_id", columns={"id"}),
 *        @ORM\Index(name="IDX_exercise_updater", columns={"updater"})
 *    }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *   normalizationContext={"groups" = {"read"}},
 *   denormalizationContext={"groups" = {"write"}},
 *   collectionOperations={
 *     "get",
 *     "post",
 *     "get_images_for_exercise" = {
 *        "method" = "GET",
 *        "path" = "/exercises/{id}/images",
 *        "controller" = ExerciseImageController::class,
 *        "read" = false,
 *        "openapi_context" = {
 *          "parameters" = {
 *           {
 *             "name" = "id",
 *             "in" = "path",
 *             "description" = "id of exercise",
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
 *     "delete_exercise_image" = {
 *       "method" = "DELETE",
 *       "path" = "/exercises/{id}/image/{fileName}",
 *       "controller" = ExerciseImageDeleteController::class
 *     },
 *     "post_upload_images" = {
 *       "method" = "POST",
 *       "path" = "/exercises/images",
 *       "controller" = ExerciseImageUploadController::class,
 *       "deserialize" = false,
 *       "openapi_context" = {
 *         "requestBody" = {
 *           "description" = "File upload to an existing resource (exercise)",
 *           "required" = true,
 *           "content" = {
 *             "multipart/form-data" = {
 *               "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                   "file" = {
 *                     "type" = "string",
 *                     "format" = "binary",
 *                     "description" = "Upload images for exercise",
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
 *     "patch",
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
class Exercises
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
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     * @Groups({"read", "write"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="special_features", type="text", length=65535, nullable=false, options={"comment"="Besonderheiten"})
     * @Groups({"read", "write"})
     */
    private $specialFeatures;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_picture_path", type="string", length=250, nullable=false)
     * @Groups({"read", "write"})
     * @ApiProperty(
     *   iri="http://schema.org/image",
     *   attributes={
     *     "openapi_context"={
     *       "type"="string",
     *     }
     *   }
     * )
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
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(name="updater", referencedColumnName="id", nullable=true)
     * @Groups({"read"})
     */
    private $updater;

    /**
     * @var Collection|ExerciseOption[]
     *
     * @ORM\OneToMany(targetEntity=ExerciseXExerciseOption::class, mappedBy="exercise", cascade={"ALL"}, orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $exerciseXExerciseOptions;

    /**
     * @var Collection|DeviceOption[]
     *
     * @ORM\OneToMany(targetEntity=ExerciseXDeviceOption::class, mappedBy="exercise", cascade={"ALL"}, orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $exerciseXDeviceOptions;

    /**
     * @var ArrayCollection|Device[]
     *
     * @ORM\OneToMany(targetEntity=ExerciseXDevice::class, mappedBy="exercise", cascade={"ALL"}, orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $exerciseXDevices;

    /**
     * @var ArrayCollection|ExerciseType[]
     *
     * @ORM\OneToMany(targetEntity=ExerciseXExerciseType::class, mappedBy="exercise", cascade={"ALL"}, orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $exerciseXExerciseType;

    public function __construct()
    {
      $this->exerciseXDeviceOptions = new ArrayCollection();
      $this->exerciseXExerciseOptions = new ArrayCollection();
      $this->exerciseXDevice = new ArrayCollection();
      $this->exerciseXExerciseType = new ArrayCollection();
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
     * Get the value of description
     *
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of specialFeatures
     *
     * @return  string
     */
    public function getSpecialFeatures()
    {
        return $this->specialFeatures;
    }

    /**
     * Set the value of specialFeatures
     *
     * @param  string  $specialFeatures
     *
     * @return  self
     */
    public function setSpecialFeatures(string $specialFeatures)
    {
        $this->specialFeatures = $specialFeatures;

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
    public function setUpdater(?Users $updater)
    {
      $this->updater = $updater;

      return $this;
    }

    public function getExerciseXExerciseOptions()
    {
      return $this->exerciseXExerciseOptions;
    }

    public function getExerciseXDeviceOptions()
    {
      return $this->exerciseXDeviceOptions;
    }

    public function getExerciseXDevices()
    {
      return $this->exerciseXDevices;
    }

    public function getExerciseXExerciseType()
    {
      return $this->exerciseXExerciseType;
    }

    public function addExerciseXDeviceOption(ExerciseXDeviceOption $exerciseXDeviceOption)
    {
        if ($this->exerciseXDeviceOptions->contains($exerciseXDeviceOption)) {
            return;
        }

        $this->exerciseXDeviceOptions->add($exerciseXDeviceOption);
        $exerciseXDeviceOption->setExercise($this);
    }

    /**
     * @param ExerciseXDeviceOption $exerciseXDeviceOption
     */
    public function removeExerciseXDeviceOption(ExerciseXDeviceOption $exerciseXDeviceOption)
    {
        if (!$this->exerciseXDeviceOptions->contains($exerciseXDeviceOption)) {
            return;
        }

        $this->exerciseXDeviceOptions->removeElement($exerciseXDeviceOption);
        $exerciseXDeviceOption->setExercise(null);
    }

    public function addExerciseXExerciseOption(ExerciseXExerciseOption $exerciseXExerciseOption)
    {
        if ($this->exerciseXExerciseOptions->contains($exerciseXExerciseOption)) {
            return;
        }

        $this->exerciseXExerciseOptions->add($exerciseXExerciseOption);
        $exerciseXExerciseOption->setExercise($this);
    }

    /**
     * @param ExerciseXExerciseOption $exerciseXExerciseOption
     */
    public function removeExerciseXExerciseOption(ExerciseXExerciseOption $exerciseXExerciseOption)
    {
        if (!$this->exerciseXExerciseOptions->contains($exerciseXExerciseOption)) {
            return;
        }

        $this->exerciseXExerciseOptions->removeElement($exerciseXExerciseOption);
        $exerciseXExerciseOption->setExercise(null);
    }

    public function addExerciseXExerciseType(ExerciseXExerciseType $exerciseXExerciseType)
    {
      if ($this->exerciseXExerciseType->contains($exerciseXExerciseType)) {
          return;
      }

      $this->exerciseXExerciseType->add($exerciseXExerciseType);
      $exerciseXExerciseType->setExercise($this);
    }

    /**
     * @param ExerciseXExerciseType $exerciseXExerciseType
     */
    public function removeExerciseXExerciseType(ExerciseXExerciseType $exerciseXExerciseType)
    {
      if (!$this->exerciseXExerciseType->contains($exerciseXExerciseType)) {
          return;
      }

      $this->exerciseXExerciseType->removeElement($exerciseXExerciseType);
      $exerciseXExerciseType->setExercise(null);
    }

    public function addExerciseXDevice(ExerciseXDevice $exerciseXDevice)
    {
      if ($this->exerciseXDevices->contains($exerciseXDevice)) {
          return;
      }

      $this->exerciseXDevices->add($exerciseXDevice);
      $exerciseXDevice->setExercise($this);
    }

    /**
     * @param ExerciseXDevice $exerciseXDevice
     */
    public function removeExerciseXDevice(ExerciseXDevice $exerciseXDevice)
    {
      if (!$this->exerciseXDevices->contains($exerciseXDevice)) {
          return;
      }

      $this->exerciseXDevices->removeElement($exerciseXDevice);
      $exerciseXDevice->setExercise(null);
    }
}
