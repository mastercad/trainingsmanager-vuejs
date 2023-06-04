<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
 */
class DeviceXDeviceGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Devices
     *
     * @ORM\ManyToOne(targetEntity="Devices")
     * @ORM\JoinColumn(name="device", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     */
    private $device;

    /**
     * @var DeviceGroups
     *
     * @ORM\ManyToOne(targetEntity="DeviceGroups")
     * @ORM\JoinColumn(name="device_group", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     */
    private $deviceGroup;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="creator", nullable=false, referencedColumnName="id")
     */
    private $creator;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumn(name="updater", referencedColumnName="id")
     */
    private $updater;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;
}
