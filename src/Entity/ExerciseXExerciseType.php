<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXExerciseType
 *
 * @ORM\Table(name="exercise_x_exercise_type", uniqueConstraints={@ORM\UniqueConstraint(name="unique_exercise_x_exercise_type_id", columns={"id"})}, indexes={@ORM\Index(name="exercise_x_exercise_type_creator", columns={"creator"}), @ORM\Index(name="exercise_x_exercise_type_exercise", columns={"exercise"}), @ORM\Index(name="exercise_x_exercise_type_exercise_type", columns={"exercise_type"}), @ORM\Index(name="exercise_x_exercise_type_updater", columns={"updater"})})
 * @ORM\Entity
 */
class ExerciseXExerciseType
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
     * @var \Exercises
     *
     * @ORM\ManyToOne(targetEntity="Exercises")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise", referencedColumnName="id")
     * })
     */
    private $exercise;

    /**
     * @var \ExerciseTypes
     *
     * @ORM\ManyToOne(targetEntity="ExerciseTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_type", referencedColumnName="id")
     * })
     */
    private $exerciseType;

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
