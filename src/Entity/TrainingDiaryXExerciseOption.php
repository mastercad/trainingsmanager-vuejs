<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXExerciseOption
 */
#[ORM\Table(name: 'training_diary_x_exercise_option')]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_exercise_option_fk', columns: ['exercise_option'])]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_t_d_x_e_fk', columns: ['training_plan_exercise'])]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_update_user_fk', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_training_diary_x_exercise_option_id', columns: ['id'])]
#[ORM\Entity]
class TrainingDiaryXExerciseOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\JoinColumn(name: 'exercise_option', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'ExerciseOptions')]
    private ExerciseOptions $exerciseOption;

    #[ORM\JoinColumn(name: 'training_plan_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    private TrainingPlanXExercise $trainingPlanExercise;

    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    private string $optionValue;

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
     * Get the value of exerciseOption
     */
    public function getExerciseOption(): ExerciseOptions|null
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
     * Get the value of trainingPlanExercise
     */
    public function getTrainingPlanExercise(): TrainingPlanXExercise
    {
        return $this->trainingPlanExercise;
    }

    /**
     * Set the value of trainingPlanExercise
     */
    public function setTrainingPlanExercise(TrainingPlanXExercise $trainingPlanExercise): self
    {
        $this->trainingPlanExercise = $trainingPlanExercise;

        return $this;
    }

    /**
     * Get the value of optionValue
     */
    public function getOptionValue(): string
    {
        return $this->optionValue;
    }

    /**
     * Set the value of optionValue
     */
    public function setOptionValue(string $optionValue): self
    {
        $this->optionValue = $optionValue;

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
