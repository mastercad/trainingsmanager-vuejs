<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Muscles;
use App\Entity\MuscleGroups;
use App\Entity\Users;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MuscleXMuscleGroup
 *
 * @ORM\Table(
 *    name="muscle_x_muscle_group",
 *    indexes={
 *      @ORM\Index(name="muscle_group_muscle", columns={"muscle"}),
 *      @ORM\Index(name="muscle_x_muscle_group_creator", columns={"creator"}),
 *      @ORM\Index(name="muscle_x_muscle_group_id", columns={"id"}),
 *      @ORM\Index(name="muscle_x_muscle_group_muscle_group", columns={"muscle_group"}),
 *      @ORM\Index(name="muscle_x_muscle_group_updater", columns={"updater"})
 *    }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *   normalizationContext={"groups" = {"read"}},
 *   denormalizationContext={"groups" = {"write"}},
 *   itemOperations={
 *     "get"={"method"="GET"},
 *     "put"={"method"="PUT"},
 *     "delete"={"method"="DELETE"}
 *   },
 *   collectionOperations={
 *     "get"={"method"="GET"},
 *     "post"={"method"="POST"}
 *   }
 * )
 */
class MuscleXMuscleGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @var Muscles
     *
     * @ORM\ManyToOne(targetEntity=Muscles::class, inversedBy="muscleXMuscleGroups")
     * @ORM\JoinColumn(name="muscle", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read", "write"})
     */
    private $muscle;

    /**
     * @var MuscleGroups
     *
     * @ORM\ManyToOne(targetEntity=MuscleGroups::class)
     * @ORM\JoinColumn(name="muscle_group", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read", "write"})
     */
    private $muscleGroup;

    /**
     * @var int
     *
     * @ORM\Column(name="strain", type="integer", nullable=false, options={"comment"="Beanspruchung des Muskels"})
     * @Groups({"read", "write"})
     */
    private $strain;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @Groups({"read"})
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Groups({"read"})
     */
    private $updated;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $creator;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $updater;

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
     * Get the value of use
     *
     * @return int
     */
    public function getStrain()
    {
        return $this->strain;
    }

    /**
     * Set the value of use
     *
     * @param int $strain
     *
     * @return self
     */
    public function setStrain(int $strain)
    {
        $this->strain = $strain;

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
     * @return  self
     */
    public function setCreator(Users $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of muscle
     *
     * @return ?Muscles
     */
    public function getMuscle()
    {
        return $this->muscle;
    }

    /**
     * Set the value of muscle
     *
     * @param Muscles $muscle
     *
     * @return self
     */
    public function setMuscle(?Muscles $muscle)
    {
        $this->muscle = $muscle;

        return $this;
    }

    /**
     * Get the value of muscleGroup
     *
     * @return MuscleGroups
     */
    public function getMuscleGroup()
    {
        return $this->muscleGroup;
    }

    /**
     * Set the value of muscleGroup
     *
     * @param ?MuscleGroups $muscleGroup
     *
     * @return self
     */
    public function setMuscleGroup(?MuscleGroups $muscleGroup)
    {
        $this->muscleGroup = $muscleGroup;

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
}
