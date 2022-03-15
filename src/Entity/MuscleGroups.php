<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MuscleGroups
 *
 * @ORM\Table(name="muscle_groups", uniqueConstraints={@ORM\UniqueConstraint(name="unique_muscle_group_name", columns={"name"}), @ORM\UniqueConstraint(name="unique_muscle_group_seo_link", columns={"seo_link"})}, indexes={@ORM\Index(name="muscle_group_creator", columns={"creator"}), @ORM\Index(name="muscle_group_id", columns={"id"}), @ORM\Index(name="muscle_group_updater", columns={"updater"})})
 * @ORM\Entity
 */
class MuscleGroups
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
     * @ORM\Column(name="color", type="string", length=250, nullable=false)
     */
    private $color;

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
