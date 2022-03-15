<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseOptions
 *
 * @ORM\Table(name="exercise_options", uniqueConstraints={@ORM\UniqueConstraint(name="exercise_option_name", columns={"name"}), @ORM\UniqueConstraint(name="unique_exercise_option_id", columns={"id"})}, indexes={@ORM\Index(name="exercise_option_creator", columns={"creator"}), @ORM\Index(name="exercise_option_updater", columns={"updater"})})
 * @ORM\Entity
 */
class ExerciseOptions
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
