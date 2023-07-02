<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * TrainingPlanXDeviceOption
 */
#[ORM\Table(name: 'training_plan_x_device_option')]
#[ORM\Index(name: 'training_plan_x_device_option_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_plan_x_device_option_device_option_fk', columns: ['device_option'])]
#[ORM\Index(name: 'training_plan_x_device_option_id', columns: ['id'])]
#[ORM\Index(name: 'training_plan_x_device_option_training_plan_exercise_fk', columns: ['training_plan_x_exercise'])]
#[ORM\Index(name: 'training_plan_x_device_option_update_user_fk', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_training_plan_x_device_option_id', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class TrainingPlanXDeviceOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $optionValue;

    #[ORM\ManyToOne(targetEntity: DeviceOptions::class)]
    #[ORM\JoinColumn(name: 'device_option', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private DeviceOptions $deviceOption;

    #[ORM\ManyToOne(targetEntity: TrainingPlanXExercise::class, inversedBy: 'trainingPlanXDeviceOptions')]
    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private TrainingPlanXExercise $trainingPlanXExercise;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $updater;

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
