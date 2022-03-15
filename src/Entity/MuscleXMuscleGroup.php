<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MuscleXMuscleGroup
 *
 * @ORM\Table(name="muscle_x_muscle_group", indexes={@ORM\Index(name="muscle_group_muscle", columns={"muscle"}), @ORM\Index(name="muscle_x_muscle_group_creator", columns={"creator"}), @ORM\Index(name="muscle_x_muscle_group_id", columns={"id"}), @ORM\Index(name="muscle_x_muscle_group_muscle_group", columns={"muscle_group"}), @ORM\Index(name="muscle_x_muscle_group_updater", columns={"updater"})})
 * @ORM\Entity
 */
class MuscleXMuscleGroup
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
     * @var int
     *
     * @ORM\Column(name="use", type="integer", nullable=false, options={"comment"="Beanspruchung des Muskels"})
     */
    private $use;

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
     * @var \Muscles
     *
     * @ORM\ManyToOne(targetEntity="Muscles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="muscle", referencedColumnName="id")
     * })
     */
    private $muscle;

    /**
     * @var \MuscleGroups
     *
     * @ORM\ManyToOne(targetEntity="MuscleGroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="muscle_group", referencedColumnName="id")
     * })
     */
    private $muscleGroup;

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
