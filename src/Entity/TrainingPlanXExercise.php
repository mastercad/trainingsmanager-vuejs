<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
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
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'exercise_order', type: 'integer', nullable: false, options: ['default' => '0', 'comment' => 'ist gedacht um die reihenfolge der übungen nachträglich noch ändern zu können'])]
    #[Groups(['read', 'write'])]
    private int $order = 0;

    #[ORM\Column(name: 'remark', type: 'text', length: 65535, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $remark;

    #[ORM\ManyToOne(targetEntity: Exercises::class)]
    #[ORM\JoinColumn(name: 'exercise', referencedColumnName: 'id', nullable: false)]
    #[Groups(['read', 'write'])]
    private Exercises $exercise;

    #[ORM\ManyToOne(targetEntity: TrainingPlans::class, inversedBy: 'trainingPlanExercises')]
    #[ORM\JoinColumn(name: 'training_plan', referencedColumnName: 'id', nullable: false)]
    #[Groups(['read', 'write'])]
    private TrainingPlans $trainingPlan;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $updater;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

    /** @var Collection|TrainingPlanXExerciseOption[] */
    #[ORM\OneToMany(targetEntity: TrainingPlanXExerciseOption::class, mappedBy: 'trainingPlanXExercise', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private Collection $trainingPlanXExerciseOptions;

    /** @var Collection|TrainingPlanXDeviceOption[] */
    #[ORM\OneToMany(targetEntity: TrainingPlanXDeviceOption::class, mappedBy: 'trainingPlanXExercise', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private Collection $trainingPlanXDeviceOptions;

    public function __construct()
    {
        $this->trainingPlanXExerciseOptions = new ArrayCollection();
        $this->trainingPlanXDeviceOptions = new ArrayCollection();
    }

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
     * Get the value of oOrder
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * Set the value of order
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of remark
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * Set the value of remark
     */
    public function setRemark(string $remark): self
    {
        $this->remark = $remark;

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
     * Get the value of trainingPlan
     */
    public function getTrainingPlan(): TrainingPlans
    {
        return $this->trainingPlan;
    }

    /**
     * Set the value of trainingPlan
     */
    public function setTrainingPlan(TrainingPlans $trainingPlan): self
    {
        $this->trainingPlan = $trainingPlan;

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

    /** @return TrainingPlanXExerciseOption[] */
    public function getTrainingPlanXExerciseOptions(): Collection
    {
        return $this->trainingPlanXExerciseOptions;
    }

    public function addTrainingXPlanExerciseOption(TrainingPlanXExerciseOption $trainingPlanXExerciseOption): void
    {
        if ($this->trainingPlanXExerciseOptions->contains($trainingPlanXExerciseOption)) {
            return;
        }

        $this->trainingPlanXExerciseOptions->add($trainingPlanXExerciseOption);
        $trainingPlanXExerciseOption->setTrainingPlanXExercise($this);
    }

    public function removeTrainingXPlanExerciseOption(TrainingPlanXExerciseOption $trainingPlanXExerciseOption): void
    {
        if (! $this->trainingPlanXExerciseOptions->contains($trainingPlanXExerciseOption)) {
            return;
        }

        $this->trainingPlanXExerciseOptions->removeElement($trainingPlanXExerciseOption);
        $trainingPlanXExerciseOption->setTrainingPlanXExercise(null);
    }

    /** @return TrainingPlanXDeviceOption[] */
    public function getTrainingPlanXDeviceOptions(): Collection
    {
        return $this->trainingPlanXDeviceOptions;
    }

    public function addTrainingXPlanDeviceOption(TrainingPlanXDeviceOption $trainingPlanXDeviceOption): void
    {
        if ($this->trainingPlanXDeviceOptions->contains($trainingPlanXDeviceOption)) {
            return;
        }

        $this->trainingPlanXDeviceOptions->add($trainingPlanXDeviceOption);
        $trainingPlanXDeviceOption->setTrainingPlanXExercise($this);
    }

    /** @param ExerciseXDevice $exerciseXDevice */
    public function removeTrainingXPlanDeviceOption(TrainingPlanXDeviceOption $trainingPlanXDeviceOption): void
    {
        if (! $this->trainingPlanXDeviceOptions->contains($trainingPlanXDeviceOption)) {
            return;
        }

        $this->trainingPlanXDeviceOptions->removeElement($trainingPlanXDeviceOption);
        $trainingPlanXDeviceOption->setTrainingPlanXExercise(null);
    }
}
