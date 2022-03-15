<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceXDeviceOption
 *
 * @ORM\Table(name="device_x_device_option", uniqueConstraints={@ORM\UniqueConstraint(name="unique_geraet_geraet_option_id", columns={"id"})}, indexes={@ORM\Index(name="device_x_device_option_creator", columns={"creator"}), @ORM\Index(name="device_x_device_option_device", columns={"device"}), @ORM\Index(name="device_x_device_option_device_option", columns={"device_option"}), @ORM\Index(name="device_x_device_option_updater", columns={"updater"})})
 * @ORM\Entity
 */
class DeviceXDeviceOption
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
     * @var string
     *
     * @ORM\Column(name="device_option_value", type="string", length=255, nullable=false)
     */
    private $deviceOptionValue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Devices
     *
     * @ORM\ManyToOne(targetEntity="Devices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device", referencedColumnName="id")
     * })
     */
    private $device;

    /**
     * @var \DeviceOptions
     *
     * @ORM\ManyToOne(targetEntity="DeviceOptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device_option", referencedColumnName="id")
     * })
     */
    private $deviceOption;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;


}
