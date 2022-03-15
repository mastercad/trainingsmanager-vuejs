<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXMuscle
 *
 * @ORM\Table(name="exercise_x_muscle", indexes={@ORM\Index(name="exercise_x_muscle_creator", columns={"creator"}), @ORM\Index(name="exercise_x_muscle_exercise", columns={"exercise"}), @ORM\Index(name="exercise_x_muscle_id", columns={"id"}), @ORM\Index(name="exercise_x_muscle_muscle", columns={"muscle"}), @ORM\Index(name="exercise_x_muscle_updater", columns={"updater"})})
 * @ORM\Entity
 */
class ExerciseXMuscle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="muscle_use", type="integer", nullable=false, options={"comment"="Beanspruchung des Muskels"})
     */
    private $muscleUse;

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
     * @var \Exercises
     *
     * @ORM\ManyToOne(targetEntity="Exercises")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise", referencedColumnName="id")
     * })
     */
    private $exercise;

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
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;


}
