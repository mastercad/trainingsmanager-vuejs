<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * TrainingPlanXExerciseOption
 */
#[ORM\Table(name: 'training_plan_x_exercise_option')]
#[ORM\Index(name: 'training_plan_x_exercise_option_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_plan_x_exercise_option_exercise_option_fk', columns: ['exercise_option'])]
#[ORM\Index(name: 'training_plan_x_exercise_option_training_plan_exercise_fk', columns: ['training_plan_x_exercise'])]
#[ORM\Index(name: 'training_plan_x_exercise_option_update_user_fk', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_training_plan_x_exercise_option_id', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class TrainingPlanXExerciseOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $optionValue;

    #[ORM\ManyToOne(targetEntity: 'ExerciseOptions')]
    #[ORM\JoinColumn(name: 'exercise_option', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private ExerciseOptions $exerciseOption;

    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise', inversedBy: 'trainingPlanXExerciseOptions')]
    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private TrainingPlanXExercise $trainingPlanXExercise;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false)]
    #[Groups(['read', 'write'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id', nullable: true)]
    #[Groups(['read', 'write'])]
    private Users|null $updater = null;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read', 'write'])]
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
     * Get the value of exerciseOption
     */
    public function getExerciseOption(): ExerciseOptions
    {
        return $this->exerciseOption;
    }

    /**
     * Set the value of exerciseOption
     */
    public function setExerciseOption(ExerciseOptions $exerciseOption): self
    {
        $this->exerciseOption = $exerciseOption;

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
    public function setTrainingPlanXExercise(TrainingPlanXExercise|null $trainingPlanXExercise): self
    {
        $this->trainingPlanXExercise = $trainingPlanXExercise;

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
