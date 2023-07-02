<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MuscleXMuscleGroup
 */
#[ORM\Table(name: 'muscle_x_muscle_group')]
#[ORM\Index(name: 'muscle_group_muscle', columns: ['muscle'])]
#[ORM\Index(name: 'muscle_x_muscle_group_creator', columns: ['creator'])]
#[ORM\Index(name: 'muscle_x_muscle_group_id', columns: ['id'])]
#[ORM\Index(name: 'muscle_x_muscle_group_muscle_group', columns: ['muscle_group'])]
#[ORM\Index(name: 'muscle_x_muscle_group_updater', columns: ['updater'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class MuscleXMuscleGroup
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Muscles::class, inversedBy: 'muscleXMuscleGroups')]
    #[ORM\JoinColumn(name: 'muscle', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private Muscles $muscle;

    #[ORM\ManyToOne(targetEntity: MuscleGroups::class)]
    #[ORM\JoinColumn(name: 'muscle_group', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private MuscleGroups $muscleGroup;

    #[ORM\Column(name: 'strain', type: 'integer', nullable: false, options: ['comment' => 'Beanspruchung des Muskels'])]
    #[Groups(['read', 'write'])]
    private int $strain;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created = 'CURRENT_TIMESTAMP';

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $updater;

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
     * Get the value of use
     */
    public function getStrain(): int
    {
        return $this->strain;
    }

    /**
     * Set the value of use
     */
    public function setStrain(int $strain): self
    {
        $this->strain = $strain;

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
     * Get the value of muscle
     */
    public function getMuscle(): Muscles|null
    {
        return $this->muscle;
    }

    /**
     * Set the value of muscle
     */
    public function setMuscle(Muscles|null $muscle): self
    {
        $this->muscle = $muscle;

        return $this;
    }

    /**
     * Get the value of muscleGroup
     */
    public function getMuscleGroup(): MuscleGroups
    {
        return $this->muscleGroup;
    }

    /**
     * Set the value of muscleGroup
     */
    public function setMuscleGroup(MuscleGroups|null $muscleGroup): self
    {
        $this->muscleGroup = $muscleGroup;

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
