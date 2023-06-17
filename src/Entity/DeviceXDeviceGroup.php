<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DeviceXDeviceGroup
 *
 * @ORM\Table(
 *    name="device_x_device_group",
 *    indexes={
 *      @ORM\Index(name="device_x_device_group_creator", columns={"creator"}),
 *      @ORM\Index(name="device_x_device_group_device", columns={"device"}),
 *      @ORM\Index(name="device_x_device_group_device_group", columns={"device_group"}),
 *      @ORM\Index(name="device_x_device_group_id", columns={"id"}),
 *      @ORM\Index(name="device_x_device_group_updater", columns={"updater"})
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
class DeviceXDeviceGroup
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
     * @var Devices
     *
     * @ORM\ManyToOne(targetEntity="Devices", inversedBy="deviceXDeviceGroups")
     * @ORM\JoinColumn(name="device", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read", "write"})
     */
    private $device;

    /**
     * @var DeviceGroups
     *
     * @ORM\ManyToOne(targetEntity="DeviceGroups")
     * @ORM\JoinColumn(name="device_group", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"read", "write"})
     */
    private $deviceGroup;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="creator", nullable=false, referencedColumnName="id")
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
     * Get the value of device
     *
     * @return ?Devices
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set the value of device
     *
     * @param ?Devices $device
     *
     * @return self
     */
    public function setDevice(?Devices $device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get the value of deviceGroup
     *
     * @return ?DeviceGroups
     */
    public function getDeviceGroup()
    {
        return $this->deviceGroup;
    }

    /**
     * Set the value of deviceGroup
     *
     * @param ?DeviceGroups $deviceGroup
     *
     * @return self
     */
    public function setDeviceGroup(?DeviceGroups $deviceGroup)
    {
        $this->deviceGroup = $deviceGroup;

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
