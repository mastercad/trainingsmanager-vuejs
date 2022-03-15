<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXDevice
 *
 * @ORM\Table(name="exercise_x_device", uniqueConstraints={@ORM\UniqueConstraint(name="unique_exercise_x_device_id", columns={"id"})}, indexes={@ORM\Index(name="exercise_x_device_creator", columns={"creator"}), @ORM\Index(name="exercise_x_device_device", columns={"device"}), @ORM\Index(name="exercise_x_device_updater", columns={"updater"}), @ORM\Index(name="index_exercise_x_device_exercise", columns={"exercise"})})
 * @ORM\Entity
 */
class ExerciseXDevice
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
