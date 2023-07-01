<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * TrainingPlanXExercise
 */
#[ORM\Table(name: 'training_plan_x_exercise')]
#[ORM\Index(name: 'IDX_training_plan_x_exercise_creator', columns: ['creator'])]
#[ORM\Index(name: 'IDX_training_plan_x_exercise_id', columns: ['id'])]
#[ORM\Index(name: 'IDX_training_plan_x_exercise_training_plan', columns: ['training_plan'])]
#[ORM\Index(name: 'IDX_training_plan_x_exercise_updater', columns: ['updater'])]
#[ORM\Index(name: 'IDX_training_plan_x_exercise_exercise', columns: ['exercise'])]
#[ORM\UniqueConstraint(name: 'training_plan_exercise', columns: ['exercise', 'training_plan'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class TrainingPlanXExercise
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
     * @var int
     */
    #[ORM\Column(name: 'exercise_order', type: 'integer', nullable: false, options: ['default' => '0', 'comment' => 'ist gedacht um die reihenfolge der übungen nachträglich noch ändern zu können'])]
    #[Groups(['read', 'write'])]
    private $order = 0;

    /**
     * @var string
     */
    #[ORM\Column(name: 'remark', type: 'text', length: 65535, nullable: false)]
    #[Groups(['read', 'write'])]
    private $remark;

    /**
     * @var Exercises
     */
    #[ORM\ManyToOne(targetEntity: Exercises::class)]
    #[ORM\JoinColumn(name: 'exercise', referencedColumnName: 'id', nullable: false)]
    #[Groups(['read', 'write'])]
    private $exercise;

    /**
     * @var TrainingPlans
     */
    #[ORM\ManyToOne(targetEntity: TrainingPlans::class, inversedBy: 'trainingPlanExercises')]
    #[ORM\JoinColumn(name: 'training_plan', referencedColumnName: 'id', nullable: false)]
    #[Groups(['read', 'write'])]
    private $trainingPlan;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: Users::class)]
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
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: Users::class)]
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
     * @var Collection|TrainingPlanXExerciseOption[]
     */
    #[ORM\OneToMany(targetEntity: TrainingPlanXExerciseOption::class, mappedBy: 'trainingPlanXExercise', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $trainingPlanXExerciseOptions;

    /**
     * @var Collection|TrainingPlanXDeviceOption[]
     */
    #[ORM\OneToMany(targetEntity: TrainingPlanXDeviceOption::class, mappedBy: 'trainingPlanXExercise', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $trainingPlanXDeviceOptions;

    public function __construct()
    {
      $this->trainingPlanXExerciseOptions = new ArrayCollection();
      $this->trainingPlanXDeviceOptions = new ArrayCollection();
    }

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
     * Get the value of oOrder
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @param int $order
     *
     * @return self
     */
    public function setOrder(int $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set the value of remark
     *
     * @param string $remark
     *
     * @return self
     */
    public function setRemark(string $remark)
    {
        $this->remark = $remark;

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
     * Get the value of exercise
     *
     * @return Exercises
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * Set the value of exercise
     *
     * @param Exercises $exercise
     *
     * @return self
     */
    public function setExercise(Exercises $exercise)
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get the value of trainingPlan
     *
     * @return TrainingPlans
     */
    public function getTrainingPlan()
    {
        return $this->trainingPlan;
    }

    /**
     * Set the value of trainingPlan
     *
     * @param TrainingPlans $trainingPlan
     *
     * @return self
     */
    public function setTrainingPlan(TrainingPlans $trainingPlan)
    {
        $this->trainingPlan = $trainingPlan;

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

    public function getTrainingPlanXExerciseOptions()
    {
        return $this->trainingPlanXExerciseOptions;
    }

    public function addTrainingXPlanExerciseOption(TrainingPlanXExerciseOption $trainingPlanXExerciseOption)
    {
      if ($this->trainingPlanXExerciseOptions->contains($trainingPlanXExerciseOption)) {
          return;
      }

      $this->trainingPlanXExerciseOptions->add($trainingPlanXExerciseOption);
      $trainingPlanXExerciseOption->setTrainingPlanXExercise($this);
    }

    /**
     * @param TrainingPlanXExerciseOption $trainingPlanXExerciseOption
     */
    public function removeTrainingXPlanExerciseOption(TrainingPlanXExerciseOption $trainingPlanXExerciseOption)
    {
      if (!$this->trainingPlanXExerciseOptions->contains($trainingPlanXExerciseOption)) {
          return;
      }

      $this->trainingPlanXExerciseOptions->removeElement($trainingPlanXExerciseOption);
      $trainingPlanXExerciseOption->setTrainingPlanXExercise(null);
    }

    public function getTrainingPlanXDeviceOptions()
    {
        return $this->trainingPlanXDeviceOptions;
    }

    public function addTrainingXPlanDeviceOption(TrainingPlanXDeviceOption $trainingPlanXDeviceOption)
    {
      if ($this->trainingPlanXDeviceOptions->contains($trainingPlanXDeviceOption)) {
          return;
      }

      $this->trainingPlanXDeviceOptions->add($trainingPlanXDeviceOption);
      $trainingPlanXDeviceOption->setTrainingPlanXExercise($this);
    }

    /**
     * @param ExerciseXDevice $exerciseXDevice
     */
    public function removeTrainingXPlanDeviceOption(TrainingPlanXDeviceOption $trainingPlanXDeviceOption)
    {
      if (!$this->trainingPlanXDeviceOptions->contains($trainingPlanXDeviceOption)) {
          return;
      }

      $this->trainingPlanXDeviceOptions->removeElement($trainingPlanXDeviceOption);
      $trainingPlanXDeviceOption->setTrainingPlanXExercise(null);
    }
}
