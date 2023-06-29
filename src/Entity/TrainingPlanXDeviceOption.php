<?php

namespace App\Entity;

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
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private $optionValue;

    /**
     * @var \DeviceOptions
     */
    #[ORM\ManyToOne(targetEntity: 'DeviceOptions')]
    #[ORM\JoinColumn(name: 'device_option', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private $deviceOption;

    /**
     * @var \TrainingPlanXExercise
     */
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private $trainingPlanXExercise;

    /**
     * @var \Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private $creator;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private $updater;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private $updated;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of optionValue
     *
     * @return string
     */
    public function getOptionValue()
    {
        return $this->optionValue;
    }

    /**
     * Set the value of optionValue
     *
     * @param string $optionValue
     *
     * @return self
     */
    public function setOptionValue(string $optionValue)
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    /**
     * Get the value of deviceOption
     *
     * @return DeviceOptions
     */
    public function getDeviceOption()
    {
        return $this->deviceOption;
    }

    /**
     * Set the value of deviceOption
     *
     * @param DeviceOptions $deviceOption
     *
     * @return self
     */
    public function setDeviceOption(DeviceOptions $deviceOption)
    {
        $this->deviceOption = $deviceOption;

        return $this;
    }

    /**
     * Get the value of trainingPlanXExercise
     *
     * @return TrainingPlanXExercise
     */
    public function getTrainingPlanXExercise()
    {
        return $this->trainingPlanXExercise;
    }

    /**
     * Set the value of trainingPlanXExercise
     *
     * @param ?TrainingPlanXExercise $trainingPlanXExercise
     *
     * @return self
     */
    public function setTrainingPlanXExercise(?TrainingPlanXExercise $trainingPlanXExercise)
    {
        $this->trainingPlanXExercise = $trainingPlanXExercise;

        return $this;
    }

    /**
     * Get the value of creator
     *
     * @return Users
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @param Users $creator
     *
     * @return self
     */
    public function setCreator(Users $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the value of created
     *
     * @param \DateTime $created
     *
     * @return self
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updater
     *
     * @return Users
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     *
     * @param Users $updater
     *
     * @return self
     */
    public function setUpdater(Users $updater)
    {
        $this->updater = $updater;

        return $this;
    }

    /**
     * Get the value of updated
     *
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     *
     * @param \DateTime|null $updated
     *
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
