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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DeviceXDeviceOption
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
        new Delete(),
    ],
)]
#[ORM\Table(name: 'device_x_device_option')]
#[ORM\Index(name: 'device_x_device_option_creator', columns: ['creator'])]
#[ORM\Index(name: 'device_x_device_option_device', columns: ['device'])]
#[ORM\Index(name: 'device_x_device_option_device_option', columns: ['device_option'])]
#[ORM\Index(name: 'device_x_device_option_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_device_x_device_option_name', columns: ['id'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class DeviceXDeviceOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: 'Devices', inversedBy: 'deviceXDeviceOptions')]
    #[ORM\JoinColumn(name: 'device', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private Devices $device;

    #[ORM\ManyToOne(targetEntity: 'DeviceOptions')]
    #[ORM\JoinColumn(name: 'device_option', nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Groups(['read', 'write'])]
    private DeviceOptions $deviceOption;

    #[ORM\Column(name: 'device_option_value', type: 'string', length: 255, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $deviceOptionValue;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $updater;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

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
     * Get the value of device
     */
    public function getDevice(): Devices|null
    {
        return $this->device;
    }

    /**
     * Set the value of device
     */
    public function setDevice(Devices|null $device): self
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get the value of deviceOption
     */
    public function getDeviceOption(): DeviceOptions|null
    {
        return $this->deviceOption;
    }

    /**
     * Set the value of deviceOption
     */
    public function setDeviceOption(DeviceOptions|null $deviceOption): self
    {
        $this->deviceOption = $deviceOption;

        return $this;
    }

    /**
     * Get the value of deviceOptionValue
     */
    public function getDeviceOptionValue(): string
    {
        return $this->deviceOptionValue;
    }

    /**
     * Set the value of deviceOptionValue
     */
    public function setDeviceOptionValue(string $deviceOptionValue): self
    {
        $this->deviceOptionValue = $deviceOptionValue;

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
}
