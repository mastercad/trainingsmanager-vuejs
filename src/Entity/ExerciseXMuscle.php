<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXMuscle
 */
#[ORM\Table(name: 'exercise_x_muscle')]
#[ORM\Index(name: 'exercise_x_muscle_creator', columns: ['creator'])]
#[ORM\Index(name: 'exercise_x_muscle_exercise', columns: ['exercise'])]
#[ORM\Index(name: 'exercise_x_muscle_id', columns: ['id'])]
#[ORM\Index(name: 'exercise_x_muscle_muscle', columns: ['muscle'])]
#[ORM\Index(name: 'exercise_x_muscle_updater', columns: ['updater'])]
#[ORM\Entity]
class ExerciseXMuscle
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\JoinColumn(name: 'exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Exercises')]
    private Exercises $exercise;

    #[ORM\JoinColumn(name: 'muscle', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Muscles')]
    private Muscles $muscle;

    #[ORM\Column(name: 'muscle_use', type: 'integer', nullable: false, options: ['comment' => 'Beanspruchung des Muskels'])]
    private int $muscleUse;

    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id', nullable: true)]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $updater = null;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
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
    public function setExercise(Exercises $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get the value of muscle
     */
    public function getMuscle(): Muscles
    {
        return $this->muscle;
    }

    /**
     * Set the value of muscle
     */
    public function setMuscle(Muscles $muscle): self
    {
        $this->muscle = $muscle;

        return $this;
    }

    /**
     * Get the value of muscleUse
     */
    public function getMuscleUse(): int
    {
        return $this->muscleUse;
    }

    /**
     * Set the value of muscleUse
     */
    public function setMuscleUse(int $muscleUse): self
    {
        $this->muscleUse = $muscleUse;

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
