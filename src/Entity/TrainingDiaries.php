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

/**
 * TrainingDiaries
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
#[ORM\Table(name: 'training_diaries')]
#[ORM\Index(name: 'index_training_diary_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'index_training_diary_training_plan_x_exercise_fk', columns: ['training_plan_x_exercise'])]
#[ORM\Index(name: 'index_training_diary_update_user_fk', columns: ['updater'])]
#[ORM\Index(name: 'training_diary_id', columns: ['id'])]
#[ORM\Entity]
class TrainingDiaries
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'comment', type: 'text', length: 65535, nullable: true)]
    private string|null $comment = null;

    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\JoinColumn(name: 'updater', nullable: true, referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $updater = null;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: TrainingPlanXExercise::class)]
    private TrainingPlanXExercise $trainingPlanXExercise;

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
     * Get the value of comment
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    /**
     * Get the value of trainingPlanXExercise
     */
    public function getTrainingPlanXExercise(): TrainingPlanXExercise
    {
        return $this->trainingPlanXExercise;
    }

    /**
     * Set the value of trainingPlanXExercise
     */
    public function setTrainingPlanXExercise(TrainingPlanXExercise $trainingPlanXExercise): self
    {
        $this->trainingPlanXExercise = $trainingPlanXExercise;

        return $this;
    }
}
