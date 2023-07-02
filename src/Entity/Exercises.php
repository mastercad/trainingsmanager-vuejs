<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\ExerciseImageController;
use App\Controller\ExerciseImageDeleteController;
use App\Controller\ExerciseImageUploadController;
use App\Controller\UploadImageDeleteController;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Exercises
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(
            uriTemplate: '/exercises/{id}/images',
            requirements: ['id' => '\d+'],
            controller: ExerciseImageController::class,
            read: false,
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'description' => 'id of exercise',
                        'type' => 'integer',
                        'required' => true,
                        'example' => 1,
                    ],
                ],
            ],
        ),
        new Get(),
        new GetCollection(),
        new Post(),
        new Post(
            uriTemplate: '/exercises/images',
            controller: ExerciseImageUploadController::class,
            deserialize: false,
            openapiContext: [
                'requestBody' => [
                    'description' => 'File upload to an existing resource (exercise)',
                    'required' => true,
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                        'description' => 'image path name for exercise',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ),
        new Patch(),
        new Put(),
        new Delete(
            uriTemplate: '/uploads/image/{fileName}',
            deserialize: false,
            requirements: ['fileName' => '[a-zA-Z0-9=]+'],
            controller: UploadImageDeleteController::class,
        ),
        new Delete(
            uriTemplate: '/exercises/{id}/image/{fileName}',
            deserialize: false,
            requirements: [
                'id' => '\d+',
                'fileName' => '[a-zA-Z0-9=]+',
            ],
            controller: ExerciseImageDeleteController::class,
        ),
        new Delete()
    ],
)]
#[ORM\Table(name: 'exercises')]
#[ORM\Index(name: 'IDX_exercise_creator', columns: ['creator'])]
#[ORM\Index(name: 'IDX_exercise_id', columns: ['id'])]
#[ORM\Index(name: 'IDX_exercise_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'UN_exercise_name', columns: ['name'])]
#[ORM\UniqueConstraint(name: 'UN_exercise_seo_link', columns: ['seo_link'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Exercises
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $name;

    #[ORM\Column(name: 'seo_link', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $seoLink;

    #[ORM\Column(name: 'description', type: 'text', length: 65535, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $description;

    #[ORM\Column(name: 'special_features', type: 'text', length: 65535, nullable: false, options: ['comment' => 'Besonderheiten'])]
    #[Groups(['read', 'write'])]
    private string $specialFeatures;

    #[ORM\Column(name: 'preview_picture_path', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $previewPicturePath;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created = 'CURRENT_TIMESTAMP';

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id', nullable: true)]
    #[Groups(['read'])]
    private Users $updater;

    /** @var Collection|ExerciseOption[] */
    #[ORM\OneToMany(targetEntity: ExerciseXExerciseOption::class, mappedBy: 'exercise', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups(['read', 'write'])]
    private Collection $exerciseXExerciseOptions;

    /** @var Collection|DeviceOption[] */
    #[ORM\OneToMany(targetEntity: ExerciseXDeviceOption::class, mappedBy: 'exercise', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups(['read', 'write'])]
    private Collection $exerciseXDeviceOptions;

    /** @var ArrayCollection|Device[] */
    #[ORM\OneToMany(targetEntity: ExerciseXDevice::class, mappedBy: 'exercise', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups(['read', 'write'])]
    private Collection $exerciseXDevices;

    /** @var ArrayCollection|ExerciseType[] */
    #[ORM\OneToMany(targetEntity: ExerciseXExerciseType::class, mappedBy: 'exercise', cascade: ['ALL'], orphanRemoval: true)]
    #[Groups(['read', 'write'])]
    private Collection $exerciseXExerciseType;

    public function __construct()
    {
        $this->exerciseXDeviceOptions = new ArrayCollection();
        $this->exerciseXExerciseOptions = new ArrayCollection();
        $this->exerciseXDevices = new ArrayCollection();
        $this->exerciseXExerciseType = new ArrayCollection();
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
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of specialFeatures
     */
    public function getSpecialFeatures(): string
    {
        return $this->specialFeatures;
    }

    /**
     * Set the value of specialFeatures
     */
    public function setSpecialFeatures(string $specialFeatures): self
    {
        $this->specialFeatures = $specialFeatures;

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
    public function getCreator(): Users
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
    public function setUpdater(Users|null $updater): self
    {
        $this->updater = $updater;

        return $this;
    }

    /** @return ExerciseXExerciseOption[] */
    public function getExerciseXExerciseOptions(): Collection
    {
        return $this->exerciseXExerciseOptions;
    }

    /** @return ExerciseXDeviceOption[] */
    public function getExerciseXDeviceOptions(): Collection
    {
        return $this->exerciseXDeviceOptions;
    }

    /** @return ExerciseXDevice[] */
    public function getExerciseXDevices(): Collection
    {
        return $this->exerciseXDevices;
    }

    /** @return ExerciseXExerciseType[] */
    public function getExerciseXExerciseType(): Collection
    {
        return $this->exerciseXExerciseType;
    }

    public function addExerciseXDeviceOption(ExerciseXDeviceOption $exerciseXDeviceOption): void
    {
        if ($this->exerciseXDeviceOptions->contains($exerciseXDeviceOption)) {
            return;
        }

        $this->exerciseXDeviceOptions->add($exerciseXDeviceOption);
        $exerciseXDeviceOption->setExercise($this);
    }

    public function removeExerciseXDeviceOption(ExerciseXDeviceOption $exerciseXDeviceOption): void
    {
        if (! $this->exerciseXDeviceOptions->contains($exerciseXDeviceOption)) {
            return;
        }

        $this->exerciseXDeviceOptions->removeElement($exerciseXDeviceOption);
        $exerciseXDeviceOption->setExercise(null);
    }

    public function addExerciseXExerciseOption(ExerciseXExerciseOption $exerciseXExerciseOption): void
    {
        if ($this->exerciseXExerciseOptions->contains($exerciseXExerciseOption)) {
            return;
        }

        $this->exerciseXExerciseOptions->add($exerciseXExerciseOption);
        $exerciseXExerciseOption->setExercise($this);
    }

    public function removeExerciseXExerciseOption(ExerciseXExerciseOption $exerciseXExerciseOption): void
    {
        if (! $this->exerciseXExerciseOptions->contains($exerciseXExerciseOption)) {
            return;
        }

        $this->exerciseXExerciseOptions->removeElement($exerciseXExerciseOption);
        $exerciseXExerciseOption->setExercise(null);
    }

    public function addExerciseXExerciseType(ExerciseXExerciseType $exerciseXExerciseType): void
    {
        if ($this->exerciseXExerciseType->contains($exerciseXExerciseType)) {
            return;
        }

        $this->exerciseXExerciseType->add($exerciseXExerciseType);
        $exerciseXExerciseType->setExercise($this);
    }

    public function removeExerciseXExerciseType(ExerciseXExerciseType $exerciseXExerciseType): void
    {
        if (! $this->exerciseXExerciseType->contains($exerciseXExerciseType)) {
            return;
        }

        $this->exerciseXExerciseType->removeElement($exerciseXExerciseType);
        $exerciseXExerciseType->setExercise(null);
    }

    public function addExerciseXDevice(ExerciseXDevice $exerciseXDevice): void
    {
        if ($this->exerciseXDevices->contains($exerciseXDevice)) {
            return;
        }

        $this->exerciseXDevices->add($exerciseXDevice);
        $exerciseXDevice->setExercise($this);
    }

    public function removeExerciseXDevice(ExerciseXDevice $exerciseXDevice): void
    {
        if (! $this->exerciseXDevices->contains($exerciseXDevice)) {
            return;
        }

        $this->exerciseXDevices->removeElement($exerciseXDevice);
        $exerciseXDevice->setExercise(null);
    }
}
