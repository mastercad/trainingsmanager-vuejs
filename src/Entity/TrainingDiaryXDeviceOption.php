<?php

namespace App\Entity;

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
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var DeviceOptions
     */
    #[ORM\JoinColumn(name: 'device_option', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'DeviceOptions')]
    private $deviceOption;

    /**
     * @var string
     */
    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    private $optionValue;

    /**
     * @var Users
     */
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $creator;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var Users
     */
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $updater;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private $updated;

    /**
     * @var TrainingPlanXExercise
     */
    #[ORM\JoinColumn(name: 'training_plan_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    private $trainingPlanExercise;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of optionValue
     */
    public function getOptionValue()
    {
        return $this->optionValue;
    }

    /**
     * Set the value of optionValue
     *
     * @return self
     */
    public function setOptionValue($optionValue)
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    /**
     * Get the value of created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the value of created
     *
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     *
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of creator
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @return self
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of deviceOption
     */
    public function getDeviceOption()
    {
        return $this->deviceOption;
    }

    /**
     * Set the value of deviceOption
     *
     * @return self
     */
    public function setDeviceOption($deviceOption)
    {
        $this->deviceOption = $deviceOption;

        return $this;
    }

    /**
     * Get the value of trainingPlanExercise
     */
    public function getTrainingPlanExercise()
    {
        return $this->trainingPlanExercise;
    }

    /**
     * Set the value of trainingPlanExercise
     *
     * @return self
     */
    public function setTrainingPlanExercise($trainingPlanExercise)
    {
        $this->trainingPlanExercise = $trainingPlanExercise;

        return $this;
    }

    /**
     * Get the value of updater
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     *
     * @return self
     */
    public function setUpdater($updater)
    {
        $this->updater = $updater;

        return $this;
    }
}
