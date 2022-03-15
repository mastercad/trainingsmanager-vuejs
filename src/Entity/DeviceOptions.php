<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceOptions
 *
 * @ORM\Table(name="device_options", uniqueConstraints={@ORM\UniqueConstraint(name="device_option_name", columns={"name"})}, indexes={@ORM\Index(name="device_option_create_user_fk", columns={"creator"}), @ORM\Index(name="device_option_id", columns={"id"}), @ORM\Index(name="device_option_update_user_fk", columns={"updater"})})
 * @ORM\Entity
 */
class DeviceOptions
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="default_value", type="string", length=255, nullable=false)
     */
    private $defaultValue;

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
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;


}
