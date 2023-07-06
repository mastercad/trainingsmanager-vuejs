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
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ExerciseXExerciseType
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Put(),
        new Delete()
    ]
)]
#[ORM\Table(name: 'exercise_x_exercise_type')]
#[ORM\Index(name: 'exercise_x_exercise_type_creator', columns: ['creator'])]
#[ORM\Index(name: 'exercise_x_exercise_type_exercise', columns: ['exercise'])]
#[ORM\Index(name: 'exercise_x_exercise_type_exercise_type', columns: ['exercise_type'])]
#[ORM\Index(name: 'exercise_x_exercise_type_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_exercise_x_exercise_type_id', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ExerciseXExerciseType
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: 'Exercises', inversedBy: 'exerciseXExerciseType')]
    #[ORM\JoinColumn(name: 'exercise', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private Exercises $exercise;

    #[ORM\ManyToOne(targetEntity: 'ExerciseTypes')]
    #[ORM\JoinColumn(name: 'exercise_type', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private ExerciseTypes $exerciseType;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users|null $updater = null;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

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
     * Get the value of exercise
     */
    public function getExercise(): Exercises
    {
        return $this->exercise;
    }

    /**
     * Set the value of exercise
     */
    public function setExercise(Exercises|null $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get the value of exerciseType
     */
    public function getExerciseType(): ExerciseTypes
    {
        return $this->exerciseType;
    }

    /**
     * Set the value of exerciseType
     */
    public function setExerciseType(ExerciseTypes $exerciseType): self
    {
        $this->exerciseType = $exerciseType;

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
     * Get the value of updater
     */
    public function getUpdater(): Users|null
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
}
