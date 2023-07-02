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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * TrainingPlans
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
#[ORM\Table(name: 'training_plans')]
#[ORM\Index(name: 'training_plan_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_plan_id', columns: ['id'])]
#[ORM\Index(name: 'training_plan_parent_fk', columns: ['parent'])]
#[ORM\Index(name: 'training_plan_training_plan_layout_fk', columns: ['training_plan_layout'])]
#[ORM\Index(name: 'training_plan_update_user_fk', columns: ['updater'])]
#[ORM\Index(name: 'training_plan_user_fk', columns: ['user'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class TrainingPlans
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: true)]
    #[Groups(['read', 'write'])]
    private string $name;

    #[ORM\Column(name: 'active', type: 'boolean', nullable: false, options: ['comment' => 'trainingsplan aktiv? damit nur der aktuellste angezeigt wird im tagebuch zum training'])]
    #[Groups(['read', 'write'])]
    private bool $active = false;

    #[ORM\Column(name: 'sorting', type: 'integer', nullable: false, options: ['default' => '1', 'comment' => 'ist für splitpläne gedacht um die reihenfolge zu beeinflussen'])]
    #[Groups(['read', 'write'])]
    private int $order = 1;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'user', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private Users $user;

    #[ORM\ManyToOne(targetEntity: 'TrainingPlanLayouts', cascade: ['all'], inversedBy: 'trainingPlans')]
    #[ORM\JoinColumn(name: 'training_plan_layout', referencedColumnName: 'id')]
    #[Groups(['read', 'write', 'items:training_plan_layouts:get'])]
    private TrainingPlanLayouts $trainingPlanLayout;

    #[ORM\ManyToOne(targetEntity: 'TrainingPlans', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent', referencedColumnName: 'id', nullable: true)]
    #[Groups(['read', 'write'])]
    private TrainingPlans $parent;

    /** @var Collection|TrainingPlans[] */
    #[ORM\OneToMany(targetEntity: 'TrainingPlans', mappedBy: 'parent', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private Collection $children;

    /** @var Collection|TrainingPlanXExercise[] */
    #[ORM\OneToMany(targetEntity: 'TrainingPlanXExercise', mappedBy: 'trainingPlan', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private Collection $trainingPlanExercises;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created = 'CURRENT_TIMESTAMP';

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id', nullable: true)]
    #[Groups(['read'])]
    private Users $updater;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->trainingPlanExercises = new ArrayCollection();
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
     * Get the value of name
     */
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string|null $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of active
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * Set the value of active
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of order
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
     * Get the value of parent
     */
    public function getParent(): TrainingPlans|null
    {
        return $this->parent;
    }

    /**
     * Set the value of parent
     */
    public function setParent(TrainingPlans|null $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the value of trainingPlanLayout
     */
    public function getTrainingPlanLayout(): TrainingPlanLayouts
    {
        return $this->trainingPlanLayout;
    }

    /**
     * Set the value of trainingPlanLayout
     */
    public function setTrainingPlanLayout(TrainingPlanLayouts|null $trainingPlanLayout): self
    {
        $this->trainingPlanLayout = $trainingPlanLayout;

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
     * Get the value of user
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    /**
     * Set the value of user
     */
    public function setUser(Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function addChild(TrainingPlans $trainingPlan): void
    {
        $this->children[] = $trainingPlan;
        $trainingPlan->setParent($this);
    }

    /** @return mixed[] */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addTrainingPlanExercise(TrainingPlanXExercise $trainingPlanExercises): void
    {
        $this->trainingPlanExercises[] = $trainingPlanExercises;
        $trainingPlanExercises->setTrainingPlan($this);
    }

    /** @return TrainingPlanXExercise[] */
    public function getTrainingPlanExercises(): Collection
    {
        return $this->trainingPlanExercises;
    }
}
