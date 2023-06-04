<?php

namespace App\Entity;

use App\Entity\MuscleGroups;
use App\Entity\MuscleXMuscleGroup;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;

/**
 * Muscles
 *
 * @ORM\Table(
 *    name="muscles",
 *    uniqueConstraints={
 *      @ORM\UniqueConstraint(name="muscle_name", columns={"name"}),
 *      @ORM\UniqueConstraint(name="muscle_seo_link", columns={"seo_link"})
 *    },
 *    indexes={
 *      @ORM\Index(name="muscle_creator", columns={"creator"}),
 *      @ORM\Index(name="muscle_id", columns={"id"}),
 *      @ORM\Index(name="muscle_updater", columns={"updater"})
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
class Muscles
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_link", type="string", length=250, nullable=false)
     * @Groups({"read", "write"})
     */
    private $seoLink;

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
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $creator;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $updater;

    /**
     * @var Collection|MuscleGroups[]
     *
     * @ORM\OneToMany(targetEntity=MuscleXMuscleGroup::class, mappedBy="muscle", cascade={"ALL"}, orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $muscleXMuscleGroups;

    public function __construct()
    {
        $this->muscleXMuscleGroups = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of seoLink
     *
     * @return string
     */
    public function getSeoLink()
    {
        return $this->seoLink;
    }

    /**
     * Set the value of seoLink
     *
     * @param string $seoLink
     *
     * @return self
     */
    public function setSeoLink(string $seoLink)
    {
        $this->seoLink = $seoLink;

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

    public function getMuscleXMuscleGroups()
    {
      return $this->muscleXMuscleGroups;
    }

    public function addMuscleXMuscleGroup(MuscleXMuscleGroup $muscleXMuscleGroup)
    {
        if ($this->muscleXMuscleGroups->contains($muscleXMuscleGroup)) {
            return;
        }

        $this->muscleXMuscleGroups->add($muscleXMuscleGroup);
        $muscleXMuscleGroup->setMuscle($this);
    }

    /**
     * @param MuscleXMuscleGroup $muscleXMuscleGroup
     */
    public function removeMuscleXMuscleGroup(MuscleXMuscleGroup $muscleXMuscleGroup)
    {
        if (!$this->muscleXMuscleGroups->contains($muscleXMuscleGroup)) {
            return;
        }

        $this->muscleXMuscleGroups->removeElement($muscleXMuscleGroup);
        $muscleXMuscleGroup->setMuscle(null);
    }
}
