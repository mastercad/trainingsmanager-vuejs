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
 * ExerciseXDeviceOption
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
#[ORM\Table(name: 'exercise_x_device_option')]
#[ORM\Index(name: 'exercise_x_device_option_creator', columns: ['creator'])]
#[ORM\Index(name: 'exercise_x_device_option_device_option', columns: ['device_option'])]
#[ORM\Index(name: 'exercise_x_device_option_updater', columns: ['updater'])]
#[ORM\Index(name: 'IDX_B9816180AEDAD51C', columns: ['exercise'])]
#[ORM\UniqueConstraint(name: 'exercise_id_device_option_id', columns: ['exercise', 'device_option'])]
#[ORM\UniqueConstraint(name: 'unique_exercise_x_device_option_id', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ExerciseXDeviceOption
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
    #[ORM\ManyToOne(targetEntity: 'Exercises', inversedBy: 'exerciseXDeviceOptions')]
    #[ORM\JoinColumn(name: 'exercise', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private $exercise;

    /**
     * @var DeviceOptions
     */
    #[ORM\ManyToOne(targetEntity: 'DeviceOptions')]
    #[ORM\JoinColumn(name: 'device_option', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private $deviceOption;

    /**
     * @var string
     */
    #[ORM\Column(name: 'device_option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private $deviceOptionValue;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
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
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', nullable: true, referencedColumnName: 'id')]
    #[Groups(['read'])]
    private $updater;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
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
     * Get the value of deviceOptionValue
     *
     * @return string
     */
    public function getDeviceOptionValue()
    {
        return $this->deviceOptionValue;
    }

    /**
     * Set the value of deviceOptionValue
     *
     * @param string $deviceOptionValue
     *
     * @return self
     */
    public function setDeviceOptionValue(string $deviceOptionValue)
    {
        $this->deviceOptionValue = $deviceOptionValue;

        return $this;
    }

    /**
     * Get the value of option
     *
     * @return ?DeviceOptions
     */
    public function getDeviceOption()
    {
        return $this->deviceOption;
    }

    /**
     * Set the value of option
     *
     * @param ?DeviceOptions $deviceOption
     *
     * @return self
     */
    public function setDeviceOption(?DeviceOptions $deviceOption)
    {
        $this->deviceOption = $deviceOption;

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
     * @return  self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
