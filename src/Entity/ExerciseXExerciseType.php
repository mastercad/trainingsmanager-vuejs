<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ExerciseXExerciseType
 *
 * @ORM\Table(
 *    name="exercise_x_exercise_type",
 *    uniqueConstraints={
 *      @ORM\UniqueConstraint(name="unique_exercise_x_exercise_type_id", columns={"id"})
 *    },
 *    indexes={
 *      @ORM\Index(name="exercise_x_exercise_type_creator", columns={"creator"}),
 *      @ORM\Index(name="exercise_x_exercise_type_exercise", columns={"exercise"}),
 *      @ORM\Index(name="exercise_x_exercise_type_exercise_type", columns={"exercise_type"}),
 *      @ORM\Index(name="exercise_x_exercise_type_updater", columns={"updater"})
 *    }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *   normalizationContext={"groups" = {"read"}},
 *   denormalizationContext={"groups" = {"write"}},
 *   itemOperations={
 *     "get",
 *     "patch",
 *     "delete",
 *     "put"
 *   },
 *   collectionOperations={
 *     "get",
 *     "post"
 *   }
 * )
 */
class ExerciseXExerciseType
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
     * @var Exercises
     *
     * @ORM\ManyToOne(targetEntity="Exercises", inversedBy="exerciseXExerciseType")
     * @ORM\JoinColumn(name="exercise", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read", "write"})
     */
    private $exercise;

    /**
     * @var ExerciseTypes
     *
     * @ORM\ManyToOne(targetEntity="ExerciseTypes")
     * @ORM\JoinColumn(name="exercise_type", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read", "write"})
     */
    private $exerciseType;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $creator;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @Groups({"read"})
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * @Groups({"read"})
     */
    private $updater;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Groups({"read"})
     */
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
     * @param ?Exercises $exercise
     *
     * @return self
     */
    public function setExercise(?Exercises $exercise)
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get the value of exerciseType
     *
     * @return ExerciseTypes
     */
    public function getExerciseType()
    {
        return $this->exerciseType;
    }

    /**
     * Set the value of exerciseType
     *
     * @param ExerciseTypes $exerciseType
     *
     * @return self
     */
    public function setExerciseType(ExerciseTypes $exerciseType)
    {
        $this->exerciseType = $exerciseType;

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
