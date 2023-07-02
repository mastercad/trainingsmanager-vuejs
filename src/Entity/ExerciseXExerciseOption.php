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
 * ExerciseXExerciseOption
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
#[ORM\Table(name: 'exercise_x_exercise_option')]
#[ORM\Index(name: 'exercise_x_exercise_option_creator', columns: ['creator'])]
#[ORM\Index(name: 'exercise_x_exercise_option_exercise', columns: ['exercise'])]
#[ORM\Index(name: 'exercise_x_exercise_option_exercise_option', columns: ['exercise_option'])]
#[ORM\Index(name: 'exercise_x_exercise_option_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_uebung_x_uebung_option_id', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ExerciseXExerciseOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: 'Exercises', inversedBy: 'exerciseXExerciseOptions')]
    #[ORM\JoinColumn(name: 'exercise', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private Exercises $exercise;

    #[ORM\ManyToOne(targetEntity: 'ExerciseOptions')]
    #[ORM\JoinColumn(name: 'exercise_option', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private ExerciseOptions $exerciseOption;

    #[ORM\Column(name: 'exercise_option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $exerciseOptionValue;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $updater;

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
     * Get the value of exerciseOptionValue
     */
    public function getExerciseOptionValue(): string
    {
        return $this->exerciseOptionValue;
    }

    /**
     * Set the value of exerciseOptionValue
     */
    public function setExerciseOptionValue(string $exerciseOptionValue): self
    {
        $this->exerciseOptionValue = $exerciseOptionValue;

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
     * Get the value of exerciseOption
     */
    public function getExerciseOption(): ExerciseOptions
    {
        return $this->exerciseOption;
    }

    /**
     * Set the value of exerciseOption
     */
    public function setExerciseOption(ExerciseOptions|null $exerciseOption): self
    {
        $this->exerciseOption = $exerciseOption;

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
}
