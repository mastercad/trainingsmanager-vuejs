<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXDeviceOption
 */
#[ORM\Table(name: 'training_diary_x_device_option')]
#[ORM\Index(name: 'index_training_diary_x_device_option_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'index_training_diary_x_device_option_device_option_fk', columns: ['device_option'])]
#[ORM\Index(name: 'index_training_diary_x_device_option_t_p_e_fk', columns: ['training_plan_exercise'])]
#[ORM\Index(name: 'index_training_diary_x_device_option_update_user_fk', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_training_diary_x_device_option_id', columns: ['id'])]
#[ORM\Entity]
class TrainingDiaryXDeviceOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\JoinColumn(name: 'device_option', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'DeviceOptions')]
    private DeviceOptions $deviceOption;

    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    private string $optionValue;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created = 'CURRENT_TIMESTAMP';

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'training_plan_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    private TrainingPlanXExercise $trainingPlanExercise;

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
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     */
    public function setUpdated(DateTime $updated): self
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
     * Get the value of deviceOption
     */
    public function getDeviceOption(): DeviceOptions
    {
        return $this->deviceOption;
    }

    /**
     * Set the value of deviceOption
     */
    public function setDeviceOption(DeviceOptions $deviceOption): self
    {
        $this->deviceOption = $deviceOption;

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
