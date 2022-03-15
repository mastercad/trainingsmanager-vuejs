<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXDeviceOption
 *
 * @ORM\Table(name="exercise_x_device_option", uniqueConstraints={@ORM\UniqueConstraint(name="exercise_id_device_option_id", columns={"exercise", "option"}), @ORM\UniqueConstraint(name="unique_exercise_x_device_option_id", columns={"id"})}, indexes={@ORM\Index(name="exercise_x_device_option_creator", columns={"creator"}), @ORM\Index(name="exercise_x_device_option_device_option", columns={"option"}), @ORM\Index(name="exercise_x_device_option_updater", columns={"updater"}), @ORM\Index(name="IDX_B9816180AEDAD51C", columns={"exercise"})})
 * @ORM\Entity
 */
class ExerciseXDeviceOption
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
     * @var \DeviceOptions
     *
     * @ORM\ManyToOne(targetEntity="DeviceOptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="option", referencedColumnName="id")
     * })
     */
    private $option;

    /**
     * @var \Exercises
     *
     * @ORM\ManyToOne(targetEntity="Exercises")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise", referencedColumnName="id")
     * })
     */
    private $exercise;

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
