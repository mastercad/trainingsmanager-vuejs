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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ExerciseXExerciseOption
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
#[ORM\Table(name: 'exercise_x_exercise_option')]
#[ORM\Index(name: 'exercise_x_exercise_option_creator', columns: ['creator'])]
#[ORM\Index(name: 'exercise_x_exercise_option_exercise', columns: ['exercise'])]
#[ORM\Index(name: 'exercise_x_exercise_option_exercise_option', columns: ['exercise_option'])]
#[ORM\Index(name: 'exercise_x_exercise_option_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_uebung_x_uebung_option_id', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ExerciseXExerciseOption
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
     * @var Exercises
     */
    #[ORM\ManyToOne(targetEntity: 'Exercises', inversedBy: 'exerciseXExerciseOptions')]
    #[ORM\JoinColumn(name: 'exercise', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private $exercise;

    /**
     * @var ExerciseOptions
     */
    #[ORM\ManyToOne(targetEntity: 'ExerciseOptions')]
    #[ORM\JoinColumn(name: 'exercise_option', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private $exerciseOption;

    /**
     * @var string
     */
    #[ORM\Column(name: 'exercise_option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private $exerciseOptionValue;

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
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
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
     * Get the value of exerciseOptionValue
     *
     * @return string
     */
    public function getExerciseOptionValue()
    {
        return $this->exerciseOptionValue;
    }

    /**
     * Set the value of exerciseOptionValue
     *
     * @param string $exerciseOptionValue
     *
     * @return self
     */
    public function setExerciseOptionValue(string $exerciseOptionValue)
    {
        $this->exerciseOptionValue = $exerciseOptionValue;

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
     * @param \DateTime  $created
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
     * Get the value of exerciseOption
     *
     * @return ExerciseOptions
     */
    public function getExerciseOption()
    {
        return $this->exerciseOption;
    }

    /**
     * Set the value of exerciseOption
     *
     * @param ?ExerciseOptions $exerciseOption
     *
     * @return self
     */
    public function setExerciseOption(?ExerciseOptions $exerciseOption)
    {
        $this->exerciseOption = $exerciseOption;

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
