<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devices
 *
 * @ORM\Table(name="devices", uniqueConstraints={@ORM\UniqueConstraint(name="device_name", columns={"name"}), @ORM\UniqueConstraint(name="device_seo_link", columns={"seo_link"})}, indexes={@ORM\Index(name="device_creator", columns={"creator"}), @ORM\Index(name="device_id", columns={"id"}), @ORM\Index(name="device_updater", columns={"updater"})})
 * @ORM\Entity
 */
class Devices
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
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_link", type="string", length=250, nullable=false)
     */
    private $seoLink;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_picture_path", type="string", length=250, nullable=false)
     */
    private $previewPicturePath;

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
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;


}
