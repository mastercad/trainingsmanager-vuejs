<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXTrainingPlanExercise
 */
#[ORM\Table(name: 'training_diary_x_training_plan_exercise')]
#[ORM\Index(name: 'index_training_diary_x_training_plan_exercise_training_diary_fk', columns: ['training_diary'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_id', columns: ['id'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_t_p_x_e_fk', columns: ['training_plan_x_exercise'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_update_user_fk', columns: ['updater'])]
#[ORM\Entity]
class TrainingDiaryXTrainingPlanExercise
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\JoinColumn(name: 'training_diary', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingDiaries')]
    private TrainingDiaries $trainingDiary;

    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    private TrainingPlanXExercise $trainingPlanXExercise;

    #[ORM\Column(name: 'flag_finished', type: 'boolean', nullable: false)]
    private bool $flagFinished = false;

    #[ORM\Column(name: 'comment', type: 'string', length: 255, nullable: false)]
    private string $comment;

    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false)]
    private DateTime $created;

    #[ORM\JoinColumn(name: 'updater', nullable: true, referencedColumnName: 'id')]
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
     * Get the value of trainingDiary
     */
    public function getTrainingDiary(): TrainingDiaries
    {
        return $this->trainingDiary;
    }

    /**
     * Set the value of trainingDiary
     */
    public function setTrainingDiary(TrainingDiaries $trainingDiary): self
    {
        $this->trainingDiary = $trainingDiary;

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

    /**
     * Get the value of flagFinished
     */
    public function getFlagFinished(): bool
    {
        return $this->flagFinished;
    }

    /**
     * Set the value of flagFinished
     */
    public function setFlagFinished(bool $flagFinished): self
    {
        $this->flagFinished = $flagFinished;

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
}
