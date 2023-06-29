<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\TrainingPlanLayouts;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

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
    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: true)]
    #[Groups(['read', 'write'])]
    private $name;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'active', type: 'boolean', nullable: false, options: ['comment' => 'trainingsplan aktiv? damit nur der aktuellste angezeigt wird im tagebuch zum training'])]
    #[Groups(['read', 'write'])]
    private $active = false;

    /**
     * @var integer
     */
    #[ORM\Column(name: 'sorting', type: 'integer', nullable: false, options: ['default' => '1', 'comment' => 'ist für splitpläne gedacht um die reihenfolge zu beeinflussen'])]
    #[Groups(['read', 'write'])]
    private $order = 1;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'user', referencedColumnName: 'id')]
    #[Groups(['read', 'write'])]
    private $user;

    /**
     * @var TrainingPlanLayouts
     */
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanLayouts', cascade: ['all'], inversedBy: 'trainingPlans')]
    #[ORM\JoinColumn(name: 'training_plan_layout', referencedColumnName: 'id')]
    #[Groups(['read', 'write', 'items:training_plan_layouts:get'])]
    private $trainingPlanLayout;

    /**
     * @var TrainingPlans
     */
    #[ORM\ManyToOne(targetEntity: 'TrainingPlans', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent', referencedColumnName: 'id', nullable: true)]
    #[Groups(['read', 'write'])]
    private $parent;

    /**
     * @var Collection|TrainingPlans[]
     */
    #[ORM\OneToMany(targetEntity: 'TrainingPlans', mappedBy: 'parent', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $children;

    /**
     * @var Collection|TrainingPlanXExercise[]
     */
    #[ORM\OneToMany(targetEntity: 'TrainingPlanXExercise', mappedBy: 'trainingPlan', cascade: ['persist'])]
    #[Groups(['read', 'write'])]
    private $trainingPlanExercises;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private $updated;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private $creator;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id', nullable: true)]
    #[Groups(['read'])]
    private $updater;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->trainingPlanExercises = new ArrayCollection();
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
     * Get the value of name
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param null|string $name
     *
     * @return self
     */
    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set the value of active
     *
     * @param bool $active
     *
     * @return self
     */
    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of order
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
     * Get the value of parent
     *
     * @return null|TrainingPlans
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the value of parent
     *
     * @param null|TrainingPlans $parent
     *
     * @return self
     */
    public function setParent(?TrainingPlans $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the value of trainingPlanLayout
     *
     * @return TrainingPlanLayouts
     */
    public function getTrainingPlanLayout()
    {
        return $this->trainingPlanLayout;
    }

    /**
     * Set the value of trainingPlanLayout
     *
     * @param ?TrainingPlanLayouts $trainingPlanLayout
     *
     * @return self
     */
    public function setTrainingPlanLayout(?TrainingPlanLayouts $trainingPlanLayout)
    {
        $this->trainingPlanLayout = $trainingPlanLayout;

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
     * Get the value of user
     *
     * @return Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param Users $user
     *
     * @return self
     */
    public function setUser(Users $user)
    {
        $this->user = $user;

        return $this;
    }

    public function addChild(TrainingPlans $trainingPlan)
    {
       $this->children[] = $trainingPlan;
       $trainingPlan->setParent($this);
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addTrainingPlanExercise(TrainingPlanXExercise $trainingPlanExercises)
    {
       $this->trainingPlanExercises[] = $trainingPlanExercises;
       $trainingPlanExercises->setTrainingPlan($this);
    }

    public function getTrainingPlanExercises()
    {
        return $this->trainingPlanExercises;
    }
}
