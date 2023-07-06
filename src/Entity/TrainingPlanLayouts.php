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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * TrainingPlanLayouts
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
#[ORM\Table(name: 'training_plan_layouts')]
#[ORM\Index(name: 'training_plan_layout_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_plan_layout_id', columns: ['id'])]
#[ORM\Index(name: 'training_plan_layout_update_user_fk', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'trainingsplan_layout_name', columns: ['name'])]
#[ORM\Entity]
class TrainingPlanLayouts
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', nullable: true, referencedColumnName: 'id')]
    private Users|null $updater = null;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\OneToMany(targetEntity: 'TrainingPlans', mappedBy: 'trainingPlanLayout', cascade: ['persist'])]
    private TrainingPlans $trainingPlans;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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

    /** @return Collection|TrainingPlans[] */
    public function getTrainingPlans(): Collection
    {
        return $this->trainingPlans;
    }

    public function addTrainingPlan(TrainingPlans $trainingPlan): self
    {
        if (! $this->trainingPlans->contains($trainingPlan)) {
            $this->trainingPlans[] = $trainingPlan;
            $trainingPlan->setTrainingPlanLayout($this);
        }

        return $this;
    }

    public function removeTrainingPlan(TrainingPlans $trainingPlan): self
    {
        if ($this->trainingPlans->contains($trainingPlan)) {
            $this->trainingPlans->removeElement($trainingPlan);
            // set the owning side to null (unless already changed)
            if ($trainingPlan->getTrainingPlanLayout() === $this) {
                $trainingPlan->setTrainingPlanLayout(null);
            }
        }

        return $this;
    }
}
